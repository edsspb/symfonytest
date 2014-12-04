<?php

namespace Denis\AdminBundle\Services;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\DependencyInjection\Container;
use Denis\DTestBundle\Entity\Tests;
use Denis\DTestBundle\Entity\Questions;
use Denis\DTestBundle\Entity\Answers;
use Denis\DTestBundle\Entity\UsersTests;
use Denis\DTestBundle\Entity\UsersQuestionsAnswers;
use Denis\DTestBundle\Entity\UsersAnswers;

class Admin {
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
    /** @var EntityManager */
    protected $em;

	public function __construct(Registry $doctrine, Container $container, SecurityContext $security) {
		$this->doctrine = $doctrine;
		$this->container = $container;
		$this->security = $security;
		$this->usersRep = $this->doctrine->getRepository('DenisDTestBundle:Users');
		$this->testsRep = $this->doctrine->getRepository('DenisDTestBundle:Tests');
		$this->questionsRep = $this->doctrine->getRepository('DenisDTestBundle:Questions');
		$this->answersRep = $this->doctrine->getRepository('DenisDTestBundle:Answers');
		$this->usersTestsRep = $this->doctrine->getRepository('DenisDTestBundle:UsersTests');
		$this->em = $this->doctrine->getManager();
	}

	public function getTests() {
		$tests = $this->testsRep->getTests();
		return $tests;
	}
}