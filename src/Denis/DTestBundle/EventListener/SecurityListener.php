<?php

namespace Denis\DTestBundle\EventListener;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent,
    Symfony\Component\Security\Http\Event\FilterControllerEvent,
    Symfony\Component\Security\Core\SecurityContext,
    Symfony\Component\Routing\Router,
    Symfony\Component\HttpKernel\Event\FilterResponseEvent,
    Symfony\Component\HttpKernel\Event\GetResponseEvent,
    Symfony\Component\HttpKernel\HttpKernelInterface,
    Symfony\Component\HttpKernel\KernelEvents,
    Symfony\Component\HttpFoundation\RedirectResponse,
    Symfony\Component\EventDispatcher\EventDispatcher,
    Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher,
    Symfony\Component\DependencyInjection\Container;

class SecurityListener
{
    protected $container;
    protected $security;
    protected $dispatcher;

    /**
    * Constructs a new instance of SecurityListener.
    *
    * @param Container $container
    * @param SecurityContext $security The security context
    * @param TraceableEventDispatcher $dispatcher
    */
    public function __construct(Container $container, SecurityContext $security, TraceableEventDispatcher $dispatcher)
    {
        $this->container = $container;
        $this->security = $security;
        $this->dispatcher = $dispatcher;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $this->dispatcher->addListener(KernelEvents::RESPONSE, array($this, 'onKernelResponse'));
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        if ($this->security->isGranted('ROLE_TEACHER')) {
            $response = new RedirectResponse($this->container->get('router')->generate('teacher_main'));
        } elseif ($this->security->isGranted('ROLE_USER')) {
            $response = new RedirectResponse($this->container->get('router')->generate('user_main'));
        } elseif ($this->security->isGranted('ROLE_ADMIN')) {
            $response = new RedirectResponse($this->container->get('router')->generate('admin_main'));
        } else {
            $response = new RedirectResponse($this->container->get('router')->generate('logout'));
        }
        
        $event->setResponse($response);
    }
}