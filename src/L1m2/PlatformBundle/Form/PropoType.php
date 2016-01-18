<?php
// src/L1m2/PlatformBundle/Form/PropoType.php

namespace L1m2\PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use L1m2\PlatformBundle\Entity\Proposition;

class PropoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           ->add('status','choice', array('choices' => array(Proposition::STATUS_REJETE => "REJETE",
                                                             Proposition::STATUS_ACCEPTE => "ACCEPTE"),
                                          'data' =>  Proposition::STATUS_RECU ))
           ->add('valider','submit')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'L1m2\PlatformBundle\Entity\Proposition'
        ));
    }

    public function getName()
    {
        return 'l1m2_platformbundle_propo';
    }

}


