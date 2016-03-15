<?php

namespace Soundify\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProductType extends AbstractType
{
    public $categories;
    public function __construct($categories)
    {
        $this->categories = $categories;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text',array('label'=>'Nom : '))
            ->add('shortdescription','textarea')
            ->add('longdescription', 'textarea')
            ->add('price', 'text')
            ->add('image', 'text')
            ->add('category', 'choice', array('expanded'=>false, 'multiple'=>false,'choices'=>$this->categories));
    }

    public function getName()
    {
        return 'product';
    }
}