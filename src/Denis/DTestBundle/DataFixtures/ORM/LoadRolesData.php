<?php

namespace Denis\DTestBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Denis\DTestBundle\Entity\Roles;

class LoadRolesData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
     * load new roles data
     */
    public function load(ObjectManager $manager)
    {
        $params = $this->container->getParameter('fixture');

        foreach ($params['roles'] as $key=>$item) {
            $role = new Roles();
            $role->setName($item['name']);
            $role->setRole($item['role']);
            $manager->persist($role);
            $this->addReference('roles'.$key, $role);
        }

        $manager->flush();
    }

    /**
     * @return null
     * the order in which fixtures will be loaded
     */
    public function getOrder()
    {
        return 10; // the order in which fixtures will be loaded
    }
}