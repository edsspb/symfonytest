<?php

namespace Denis\DTestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Denis\DTestBundle\Form\AuthType;
use Denis\DTestBundle\Entity\Users;

class UserController extends DefaultController {
	
	/**
    * @Route("/user/", name="user_main")
    * @Template()
    */
    public function indexAction()
    {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            return $this->redirect($this->generateUrl('user_auth'));
        }

        $user = $this->get('security.context')->getToken()->getUser();
        $tests = $this->getUserManager()->getUsersTests();

        return [
            'user' => $user,
            'tests' => $tests,
        ];
    }

    /**
    * @Route("/user/teacher/", name="teacher_main")
    * @Template()
    */
    public function teacherAction()
    {
        if (false === $this->get('security.context')->isGranted('ROLE_TEACHER')) {
            return $this->redirect($this->generateUrl('user_auth'));
        }

        $user = $this->get('security.context')->getToken()->getUser();
        $tests = $this->getTestManager()->getUncheckedTests();
        
        return [
            'user' => $user,
            'tests' => $tests,
        ];
    }

    /**
    * @Route("/", name="user_auth")
    * @Template()
    */
    public function authAction(Request $request)
    {
        if (false !== $this->get('security.context')->isGranted('ROLE_USER')) {
            return $this->redirect($this->generateUrl('user_main'));
        }

        $formErrors = NULL;
        $session = $request->getSession();
        $user = new Users();
        if($lastEmail = $session->get(SecurityContext::LAST_USERNAME)) {
            $user->setEmail($lastEmail);
            $formErrors = $this->get('validator')->validate($user, null, ['authentication']);
        }

        $form = $this->createForm('userform', $user, array(
            'action' => $this->generateUrl('login_check'),
            'method' => 'POST',
        ));

        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR))
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        else
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);

        return [
            'errors'        => $error,
            'form_errors'   => $formErrors,
            'form'          => $form->createView()
        ];
    }
}