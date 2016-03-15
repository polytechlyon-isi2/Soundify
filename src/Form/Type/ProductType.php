<?php

namespace Soundify\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProductType extends AbstractType
{
    public $categories;
    public function __construct($categories)
    {
        foreach ($categories as $id => $category) {
            $cle = $category->__toString();
             $this->categories[$cle] = $id;
        }
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text',array('label'=>'Nom : '))
            ->add('shortdescription','textarea',array('label'=>'Présentation : '))
            ->add('longdescription', 'textarea',array('label'=>'Description détaillée : '))
            ->add('price','number',array('label'=>'Prix : '))
            ->add('image', 'text',array('label'=>'Image : '))
            ->add('category', 'choice', array('expanded'=>false, 'multiple'=>false,'choices'=>$this->categories,'choice_value' => function ($category) {
                    return $category;
                },'choices_as_values'=>true,'label'=>'Catégorie : '));
    }

    public function getName()
    {
        return 'product';
    }
}