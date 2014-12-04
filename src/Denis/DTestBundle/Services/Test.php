<?php

namespace Denis\DTestBundle\Services;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Denis\DTestBundle\Entity\UsersQuestionsAnswers;
use Denis\DTestBundle\Entity\UsersAnswers;
use Denis\DTestBundle\Entity\UsersTests;
use Denis\DTestBundle\Entity\Questions;
use Denis\DTestBundle\Entity\Answers;
use Denis\DTestBundle\Entity\Tests;

class Test {
	/** @var Repository\UsersRepository  */
    protected $usersRep;
    /** @var Repository\TestsRepository  */
    protected $testsRep;
    /** @var Repository\QuestionsRepository  */
    protected $questionsRep;
    /** @var Repository\AnswersRepository  */
    protected $answersRep;
    /** @var Repository\UsersTestsRepository  */
    protected $usersTestsRep;
    /** @var  Registry */
    protected $doctrine;
    /** @var Container */
    protected $container;
    /** @var SecurityContext */
    protected $security;

	public function __construct(Registry $doctrine, Container $container, SecurityContext $security) {
		$this->doctrine = $doctrine;
		$this->container = $container;
		$this->security = $security;
		$this->usersRep = $this->doctrine->getRepository('DenisDTestBundle:Users');
		$this->testsRep = $this->doctrine->getRepository('DenisDTestBundle:Tests');
		$this->questionsRep = $this->doctrine->getRepository('DenisDTestBundle:Questions');
		$this->answersRep = $this->doctrine->getRepository('DenisDTestBundle:Answers');
		$this->usersTestsRep = $this->doctrine->getRepository('DenisDTestBundle:UsersTests');
	}

	public function getListTests() {
		$result = $this->testsRep->getTests();
		return $result;
	}

	public function getUncheckedTests() {
		$result = $this->testsRep->getUncheckedTests();
		return $result;
	}

	/**
	* @param int $testId
	* @param Request $request
	* @return array
	*/
	public function getTest($testId, Request $request) {
		$test = $this->testsRep->getTest($testId);
		$questions = $this->questionsRep->getQuestionsWithAnswers($testId);
		$form = '';

		if(!empty($questions)) {
			$formBuilder = $this->container->get('form.factory')->createBuilder('form');

			foreach ($questions as $question) {
				if(!empty($question['answers'])) {
					$answers = [];
					$counter = 0;
					foreach ($question['answers'] as $answer) {
						$answers[] = [$answer['id'] => $answer['name']];
						if($answer['isValid'])
							$counter++;
					}
					$multiple = $counter > 1 ? true : false;
					$formBuilder->add($question['id'], 'choice', [
		                'label' => $question['name'],
		                'choices'   => $answers,
		                'required'  => true,
		                'expanded' => true,
  						'multiple' => $multiple
		            ]);
				} else {
					$formBuilder->add($question['id'], 'text', [
		                'label' => $question['name'],
		                'max_length' => 4096,
		                'required'  => true,
		            ]);
				}
			}

			$form = $formBuilder
				->add('testid', 'hidden', ['data' => $test[0]['id'], 'read_only' => true, 'required'  => true])
				->add('save', 'submit', ['label' => 'Сохранить'])
				->setMethod('POST')
				->getForm();

			$form->handleRequest($request);
		}

		return [
			'test' => $test,
			'questions' => $questions,
			'form' => $form,
		];
	}

	/**
	* @param array $data from $request->request->all()['form']
	* @return int|boolean int in case of succeess id - $usersTests->getId() and false in case of fail
	*/
	public function saveTestResult($data) {
		if(
			!isset($data['testid'])
			|| 
			!(int)$data['testid']
		) {
			return false;
		}

		$em = $this->doctrine->getManager();
		$testId = (int)$data['testid'];

		if(
			!($test = $this->testsRep->findOneById($testId))
			||
			!$test instanceof Tests
		) {
			return false;
		}

		$user = $this->security->getToken()->getUser();

		$attempt = (int)$this->usersTestsRep->getMaxAttempt($testId, $user->getId());
		$attempt++;

		$usersTests = new UsersTests();
		$usersTests->setTests($test);
		$usersTests->setUsers($user);
		$usersTests->setAttempt($attempt);

		foreach ($data as $key => $value) {
			if(is_int($key)) {
				$usersQuestionsAnswers = new UsersQuestionsAnswers();

				if(
					!($questions = $this->questionsRep->findOneById($key))
					||
					!$questions instanceof Questions
				) {
					continue;
				}

				$usersQuestionsAnswers->setUsersTests($usersTests);
				$usersQuestionsAnswers->setQuestions($questions);

				if(is_array($value)) {
					foreach($value as $item) {
						if(
							(int)$item > 0 
							&& 
							($answer = $this->answersRep->findOneById($item))
							&&
							$answer instanceof Answers
						) {
							$usersAnswers = new UsersAnswers();
							$usersAnswers->setAnswers($answer);
							$usersAnswers->setIsValid($answer->getIsValid());
							$usersAnswers->setUsersQuestionsAnswers($usersQuestionsAnswers);
							$usersQuestionsAnswers->addUsersAnswers($usersAnswers);
						}
					}
				} else {
					if(
						(int)$value > 0 
						&& 
						($answer = $this->answersRep->findOneById($value))
						&&
						$answer instanceof Answers
					) {
						$usersQuestionsAnswers->setAnswers($answer);
						$usersQuestionsAnswers->setIsValid($answer->getIsValid());
					} else {
						$usersQuestionsAnswers->setComments($value);
					}
				}

				$usersTests->addUsersQuestionsAnswers($usersQuestionsAnswers);
			}
		}
		
		$em->persist($usersTests);
		$em->flush();
		return $usersTests;
	}
}