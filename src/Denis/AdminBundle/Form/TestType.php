<?php

namespace Denis\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\SecurityContext;

class TestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('name', 'text', [
			'label' => 'Имя',
			'required' => true
		])
		->add('save', 'submit', ['label' => 'Сохранить']);
	}

    public function getName()
    {
		return 'testform';
    }
}
