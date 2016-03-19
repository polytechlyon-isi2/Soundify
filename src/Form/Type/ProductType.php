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
        $choices = array();
        foreach ($this->categories as $id => $category) {
            $cle = $category->__toString();
            $choices[$cle] = $id;
        }
        $builder
            ->add('name', 'text',array('label'=>'Nom : '))
            ->add('shortdescription','textarea',array('label'=>'Présentation : '))
            ->add('longdescription', 'textarea',array('label'=>'Description détaillée : '))
            ->add('price','money',array('label'=>'Prix : ','currency' => 'EUR', 'precision' => 2))
            ->add('image', 'file',array('label'=>'Image : '))
            ->add('category', 'choice', array('expanded'=>false,'multiple'=>false,'choices'=>$choices,'choice_value' => function ($choice) {
                    return $choice;
                },'choices_as_values'=>true,'label'=>'Catégorie : '));
    }

    public function getName()
    {
        return 'product';
    }
}