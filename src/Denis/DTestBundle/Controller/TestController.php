<?php

namespace Denis\DTestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Denis\DTestBundle\Entity\UsersQuestionsAnswers;
use Denis\DTestBundle\Entity\UsersTests;
use Denis\DTestBundle\Form\TestType;
use Symfony\Component\Form\Form;

class TestController extends DefaultController {
	
	/**
    * @Route("/test/", name="test_main")
    * @Template()
    */
    public function indexAction()
    {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            return $this->redirect($this->generateUrl('user_auth'));
        }

        $result = $this->getTestManager()->getListTests();
        $flashBag = $this->get('session')->getFlashBag();
        $error = $flashBag->get('error');
        $flashBag->clear();

        return [
            'error' => implode($error, ' '),
            'data' => $result,
        ];
    }

    /**
    * @Route("/test/{id}/", requirements={"id" = "\d+"}, name="test_page")
    * @Template()
    */
    public function testAction($id, Request $request)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            return $this->redirect($this->generateUrl('user_auth'));
        }

        try {
            $result = $this->getTestManager()->getTest($id, $request);
            $form = $result['form'];
        } catch (\Exception $e) {
            $logger = $this->get('logger');
            $logger->error($e->getMessage());
            $this->get('session')->getFlashBag()->set(
                'error',
                $this->container->getParameter('errors')['test']['unavailable']
            );
            return $this->redirect($this->generateUrl('test_main'));
        }

        if($form instanceof Form) {

            if($form->isValid()) {
                $data = $request->request->all()['form'];
                try {
                    $saveResult = $this->getTestManager()->saveTestResult($data);
                } catch (\Exception $e) {
                    $logger = $this->get('logger');
                    $logger->error($e->getMessage());
                    $saveResult = false;
                }

                if($saveResult instanceof UsersTests)
                    return $this->redirect($this->generateUrl('test_result', [
                        'id' => $saveResult->getId()
                    ]));

                $result['error'] = $this->container->getParameter('errors')['test']['save'];
            }

            $result['form'] = $form->createView();
        } else {
            $this->get('session')->getFlashBag()->add(
                'error',
                $this->container->getParameter('errors')['test']['unavailable']
            );
            return $this->redirect($this->generateUrl('test_main'));
        }

        return $result;
    }

    /**
    * @Route("/test/result/{id}/", requirements={"id" = "\d+"}, name="test_result")
    * @Template()
    */
    public function resultAction($id)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            return $this->redirect($this->generateUrl('user_auth'));
        }

        $result = $this->getUserManager()->getResultTest($id);

        return [
            'data' => $result
        ];
    }

    /**
    * @Route("/test/checking/{id}/{usersid}/", requirements={"id" = "\d+", "usersid" = "\d+"}, name="test_checking")
    * @Template("DenisDTestBundle:Test:result.html.twig")
    */
    public function checkingAction($id, $usersid)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            return $this->redirect($this->generateUrl('user_auth'));
        }

        $result = $this->getUserManager()->getTestForChecking($id, $usersid);

        return [
            'data' => $result
        ];
    }
}