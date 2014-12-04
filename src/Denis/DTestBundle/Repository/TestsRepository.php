<?php

namespace Denis\DTestBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;

/**
 * TestsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TestsRepository extends EntityRepository
{
	public function getTests() {
		$querybuilder = $this->createQueryBuilder('t');
        return $querybuilder->getQuery()->getArrayResult();
	}

	public function getUncheckedTests() {
		$querybuilder = $this->createQueryBuilder('t')
			->innerJoin('t.usersTests', 'ut')
			->innerJoin('ut.users', 'u')
			->addSelect('ut, u')
			->where('ut.isChecked = 0');
        return $querybuilder->getQuery()->getArrayResult();
	}

	public function getTest($testId) {
		if(empty($testId))
			return [];

		$querybuilder = $this->createQueryBuilder('t')
            ->where('t.id = :testId')
            ->setParameter(':testId', $testId);

        return $querybuilder->getQuery()->getArrayResult();
	}

	public function getTestWithQuestions($testId) {
		if(empty($testId))
			return [];

		$querybuilder = $this->createQueryBuilder('t')
			->innerJoin('t.questions', 'q')
			->leftJoin('q.answers', 'a')
            ->where('t.id = :testId')
            ->addSelect('q, a')
            ->setParameter(':testId', $testId);

        return $querybuilder->getQuery()->getArrayResult();
	}
}