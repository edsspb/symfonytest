<?php

namespace Denis\DTestBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Denis\DTestBundle\Entity\Questions;

class LoadQuestionsData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
     * @param ObjectManager $manager
     * @return null
     * load new questions for tests
     */
    public function load(ObjectManager $manager)
    {
        $params = $this->container->getParameter('fixture');

        foreach ($params['questions'] as $key=>$item) {
            $question = new Questions();
            $question->setName($item['name']);
            $question->setTests($this->getReference('tests'.$item['parent']));
            $manager->persist($question);
            $this->addReference('questions'.$key, $question);
        }

        $manager->flush();
    }

    /**
     * @return null
     * the order in which fixtures will be loaded
     */
    public function getOrder()
    {
        return 2; // the order in which fixtures will be loaded
    }
}