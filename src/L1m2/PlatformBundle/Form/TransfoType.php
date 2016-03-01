<?php
// src/L1m2/PlatformBundle/Form/TransfoType.php

namespace L1m2\PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use L1m2\PlatformBundle\Entity\Transfo;

class TransfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           ->add('auteur',   'textarea', array('attr' => array("placeholder" => "Votre pseudo",  "rows" => "1", "cols" => "40")))
           ->add('mots',     'textarea', array('label'  => false ,  'attr' => array("class" => "mots1", "hidden" => true)))
           ->add('envoyer',  'submit')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'L1m2\PlatformBundle\Entity\Transfo'
        ));
    }

    public function getName()
    {
        return 'l1m2_platformbundle_transfo';
    }

}


