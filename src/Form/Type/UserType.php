<?php

namespace Soundify\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text',array('label'=>'Nom : '))
            ->add('firstname', 'text',array('label'=>'Prénom : '))
            ->add('address', 'text',array('label'=>'Adresse : '))
            ->add('zipcode', 'text',array('label'=>'Code Postal : ', 'max_length' => 5))
            ->add('username', 'email',array('label'=>'Email (identifiant de connexion) : '))
            ->add('password', 'repeated', array(
                'type'            => 'password',
                'invalid_message' => 'The password fields must match.',
                'options'         => array('required' => true),
                'first_options'   => array('label' => 'Mot de Passe'),
                'second_options'  => array('label' => 'Vérification mot de passe'),
            ))
            ->add('role', 'choice', array(
                'choices' => array('ROLE_ADMIN' => 'Admin', 'ROLE_USER' => 'User')
            ));
    }

    public function getName()
    {
        return 'user';
    }
}