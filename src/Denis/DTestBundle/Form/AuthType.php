<?php

namespace Denis\DTestBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AuthType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('email', 'text', [
			'label' => 'Email',
			'required' => true,
		])
		->add('password', 'password', [
			'label' => 'Пароль',
			'required' => true,
		])
		->add('save', 'submit', ['label' => 'Вход']);
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
	    $resolver->setDefaults(array(
	        'validation_groups' => ['authentication'],
	    ));
	}

    public function getName()
    {
		return 'userform';
    }
}
