<?php

namespace Denis\DTestBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Denis\DTestBundle\Entity\Answers;

class LoadAnswersData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $params = $this->container->getParameter('fixture');

        foreach ($params['answers'] as $key=>$item) {
            $answer = new Answers();
            $answer->setName($item['name']);
            $answer->setIsValid($item['is_valid']);
            $answer->setQuestions($this->getReference('questions'.$item['parent']));
            $manager->persist($answer);
        }

        $manager->flush();
    }

    /**
     * @return null
     * the order in which fixtures will be loaded
     */
    public function getOrder()
    {
        return 3; // the order in which fixtures will be loaded
    }
}