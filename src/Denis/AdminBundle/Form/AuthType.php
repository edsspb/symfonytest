<?php

namespace Denis\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\SecurityContext;

class AuthType extends AbstractType
{
    /** @var Session  */
    protected $session;

    public function __construct(Session $session)
    {
    	$this->session = $session;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('name', 'text', [
			'label' => 'Логин',
			'required' => true,
			'data' => $this->session->get(SecurityContext::LAST_USERNAME)
		])
		->add('password', 'password', [
			'label' => 'Пароль',
			'required' => true,
		])
		->add('save', 'submit', ['label' => 'Вход']);
	}

    public function getName()
    {
		return 'adminform';
    }
}
