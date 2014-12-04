<?php

namespace Denis\DTestBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Collections\ArrayCollection;
use Denis\DTestBundle\Entity\Users;

class LoadUsersData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
     * load new users data
     */
    public function load(ObjectManager $manager)
    {
        $params = $this->container->getParameter('fixture');

        foreach($params['users'] as $item) {
            $user = new Users();
            $user->setUsername($item['username']);
            $user->setEmail($item['email']);
            $user->setSalt(md5(uniqid()));
            $encoder = $this->container
                ->get('security.encoder_factory')
                ->getEncoder($user);
            $user->setPassword($encoder->encodePassword($item['password'], $user->getSalt()));
            $roles = new ArrayCollection();
            foreach ($item['roles'] as $role) {
                $roles->add($this->getReference('roles'.$role));
            }
            $user->setRoles($roles);
            $manager->persist($user);
        }
        
        $manager->flush();
    }

    /**
     * @return null
     * the order in which fixtures will be loaded
     */
    public function getOrder()
    {
        return 11; // the order in which fixtures will be loaded
    }
}