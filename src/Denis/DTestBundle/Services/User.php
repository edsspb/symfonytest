<?php

namespace Denis\DTestBundle\Services;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\DependencyInjection\Container;

class User {
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

	public function getUsersTests() {
		$userId = $this->security->getToken()->getUser()->getId();
		$result = $this->usersTestsRep->getUsersTests($userId);
		return $result;
	}

	public function getResultTest($usersTestsId) {
		$userId = $this->security->getToken()->getUser()->getId();
		$result = $this->usersTestsRep->getUserTestResult($userId, $usersTestsId);
		return $result;
	}

	public function getTestForChecking($usersTestsId, $userId) {
		$result = $this->usersTestsRep->getUserTestResult($userId, $usersTestsId);
		return $result;
	}
}