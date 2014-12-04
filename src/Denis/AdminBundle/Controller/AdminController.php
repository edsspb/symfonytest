<?php

namespace Denis\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Denis\DTestBundle\Controller\DefaultController;
use Denis\AdminBundle\Form\AuthType;
use Denis\DTestBundle\Entity\Tests;
use Denis\DTestBundle\Entity\Questions;
use Denis\DTestBundle\Entity\Answers;

class AdminController extends DefaultController {
	
	/**
    * @Route("/auth", name="admin_auth")
    * @Template()
    */
    public function authAction(Request $request)
    {
        if (false !== $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            return $this->redirect($this->generateUrl('admin_main'));
        }

        $form = $this->createForm('adminform', null, array(
            'action' => $this->generateUrl('admin_login_check'),
            'method' => 'POST',
        ));

        $session = $request->getSession();

        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR))
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        else
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);

        return [
            'error' => $error,
            'form' => $form->createView()
        ];
    }

    /**
    * @Route("/", name="admin_main")
    * @Template()
    */
    public function indexAction()
    {
        return [];
    }

    /**
    * @Route("/tests/{id}/{qid}", requirements={"id" = "\d*", "qid" = "\d*"}, name="admin_tests")
    * @Template()
    */
    public function testsAction(Request $request, $id = null, $qid = null)
    {
        $return = [];
        $flashBag = $this->get('session')->getFlashBag();
        $return['error'] = $flashBag->get('error');
        $flashBag->clear();

        if(isset($id) && !empty($id)) {
            $testsRep = $this->getDoctrine()->getRepository('DenisDTestBundle:Tests');
            if(($test = $testsRep->findOneById($id)) && $test instanceof Tests) {

                if(isset($qid) && !empty($qid)) {
                    $questionsRep = $this->getDoctrine()->getRepository('DenisDTestBundle:Questions');
                    if(!($question = $questionsRep->findOneById($qid)) || !$question instanceof Questions)
                        $question = new Questions();
                } else
                    $question = new Questions();

                $question->setTests($test);
                $questionform = $this->createForm('questionform', $question, array(
                    'action' => $this->generateUrl('admin_tests', ['id' => $id , 'qid' => $qid]),
                    'method' => 'POST'
                ));
                $questionform->handleRequest($request);
                if($questionform->isValid()) {
                    try {
                        $em = $this->getDoctrine()->getManager();
                        $em->persist($question);
                        $em->flush();
                    } catch (\Exception $e) {
                        $logger = $this->get('logger');
                        $logger->error($e->getMessage());
                        $return['error'][] = $this->container->getParameter('errors')['save']['question'];
                    }
                }

                $return['questionform'] = $questionform->createView();

            } else
                $test = new Tests;
        } else
            $test = new Tests;

        $testform = $this->createForm('testform', $test, array(
            'action' => $this->generateUrl('admin_tests', ['id' => $id]),
            'method' => 'POST',
        ));

        $testform->handleRequest($request);
        if($testform->isValid()) {
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($test);
                $em->flush();
            } catch (\Exception $e) {
                $logger = $this->get('logger');
                $logger->error($e->getMessage());
                $return['error'][] = $this->container->getParameter('errors')['save']['test'];
            }
        }

        $tests = $this->getAdminManager()->getTests();

        return array_merge($return, [
            'test'  => $test,
            'tests' => $tests,
            'testform'  => $testform->createView(),
        ]);
    }
    
    /**
    * @Route("/answers/question/{id}/{aid}", requirements={"id" = "\d+", "aid" = "\d*"}, name="admin_answers")
    * @Template()
    */
    public function answersAction(Request $request, $id, $aid = null)
    {
        $return = [];
        $flashBag = $this->get('session')->getFlashBag();
        $return['error'] = $flashBag->get('error');
        $flashBag->clear();

        $questionsRep = $this->getDoctrine()->getRepository('DenisDTestBundle:Questions');
        if(($question = $questionsRep->findOneById($id)) && $question instanceof Questions) {

            if(isset($aid) && !empty($aid)) {
                $answersRep = $this->getDoctrine()->getRepository('DenisDTestBundle:Answers');
                if(!($answer = $answersRep->findOneById($aid)) || !$answer instanceof Answers)
                    $answer = new Answers();
            } else
                $answer = new Answers();

            $answer->setQuestions($question);
            $answerform = $this->createForm('answerform', $answer, array(
                'action' => $this->generateUrl('admin_answers', ['id' => $id , 'aid' => $aid]),
                'method' => 'POST'
            ));
            $answerform->handleRequest($request);
            if($answerform->isValid()) {
                try {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($answer);
                    $em->flush();
                } catch (\Exception $e) {
                    $logger = $this->get('logger');
                    $logger->error($e->getMessage());
                    $return['error'][] = $this->container->getParameter('errors')['save']['answer'];
                }
            }

            $return['answerform'] = $answerform->createView();
        } else
            $return['error'][] = $this->container->getParameter('errors')['save']['not_found'];

        return array_merge($return, [
            'question' => $question
        ]);
    }

    /**
    * @Route("/delete/{obj}/{id}/{pid}", requirements={"id" = "\d+", "pid" = "\d*", "obj"="Tests|Questions|Answers"}, name="admin_delete_obj")
    * @Template()
    */
    public function deleteAction($obj, $id, $pid = null)
    {
        $errorFlag = false;

        try {
            if(!($entity = $this->getDoctrine()->getRepository('DenisDTestBundle:'.$obj)->findOneById($id))) {
                $this->get('session')->getFlashBag()->add(
                    'error',
                    $this->container->getParameter('errors')['remove']['not_found']
                );
                $errorFlag = true;
            }
        } catch (\Exception $e) {
            $logger = $this->get('logger');
            $logger->error($e->getMessage());
            $this->get('session')->getFlashBag()->add(
                'error',
                $this->container->getParameter('errors')['remove']['not_found']
            );
            $errorFlag = true;
        }

        try {
            if(!$errorFlag) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($entity);
                $em->flush();
            }
        } catch (\Exception $e) {
            $logger = $this->get('logger');
            $logger->error($e->getMessage());
            $this->get('session')->getFlashBag()->add(
                'error',
                $this->container->getParameter('errors')['remove']['error']
            );
            $errorFlag = true;
        }

        switch ($obj) {
            case 'Answers':
                $url = $this->generateUrl('admin_answers', ['id' => $pid]);
                break;
            
            default:
                $url = $this->generateUrl('admin_tests', ['id' => $pid]);
                break;
        }

        return $this->redirect($url);
    }
}