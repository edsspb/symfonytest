<?php

namespace Denis\DTestBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Denis\DTestBundle\Entity\Tests;

class LoadTestsData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
     * load new tests data
     */
    public function load(ObjectManager $manager)
    {
        $params = $this->container->getParameter('fixture');

        foreach ($params['tests'] as $key=>$item) {
            $test = new Tests();
            $test->setName($item['name']);
            $manager->persist($test);
            $this->addReference('tests'.$key, $test);
        }

        $manager->flush();
    }

    /**
     * @return null
     * the order in which fixtures will be loaded
     */
    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
}