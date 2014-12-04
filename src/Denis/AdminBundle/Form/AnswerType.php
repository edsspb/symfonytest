<?php

namespace Denis\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\SecurityContext;

class AnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('name', 'text', [
			'label' => 'Имя',
			'required' => true
		])
		->add('is_valid', 'choice', [
			'label' => 'Верный ответ?',
			'choices' => [
				'1' => 'Да',
				'0' => 'Нет'
			],
			'required'  => true,
		    'expanded' => true,
  			'multiple' => false,
		])
		->add('save', 'submit', ['label' => 'Сохранить']);
	}

    public function getName()
    {
		return 'answerform';
    }
}
