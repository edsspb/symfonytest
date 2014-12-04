<?php

namespace Denis\DTestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
	Symfony\Component\Debug\Debug;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Denis\DTestBundle\Services\Test;

class DefaultController extends Controller
{
   
	public function setContainer(ContainerInterface $container = null){
        parent::setContainer($container);
    }

    /**
     * @return Test
     */
    protected function getTestManager()
    {
        return $this->container->get('denis_d_test.test');
    }

    /**
     * @return User
     */
    protected function getUserManager()
    {
        return $this->container->get('denis_d_test.user');
    }

    /**
     * @return Admin
     */
    protected function getAdminManager()
    {
        return $this->container->get('denis_admin.admin');
    }
}
