<?php
// src/L1m2/PlatformBundle/Form/ExtraitType.php

namespace L1m2\PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ExtraitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           ->add('extrait',     'textarea', array('attr' => array("class" => "cita1", "rows" => "5", "cols" => "65")))
           ->add('indice',      'textarea', array('attr' => array("class" => "colo1", "rows" => "1", "cols" => "65")))
           ->add('description', 'textarea', array('attr' => array("class" => "desc1", "rows" => "2", "cols" => "65")))
           ->add('reference',   'textarea', array('attr' => array("class" => "refe1", "rows" => "5", "cols" => "65")))
           ->add('auteur',      'textarea', array('attr' => array("class" => "aute1",  "rows" => "2", "cols" => "65")))
           ->add('entrees',     'textarea', array('required' => false, 'attr' => array("class" => "pioc1", "hidden" => "true")))
        //   ->add('envoyer',     'submit')
        ;
        // On ajoute une fonction qui va écouter un évènement
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,    // 1er argument : L'évènement qui nous intéresse : ici, PRE_SET_DATA
            function(FormEvent $event)   // 2e argument : La fonction à exécuter lorsque l'évènement est déclenché
            { 
                // On récupère notre objet Extrait sous-jacent
                $extrait = $event->getData();
                // Cette condition est importante, on en reparle plus loin
                if (null === $extrait)
                {
                    return; // On sort de la fonction sans rien faire lorsque $Extrait vaut null
                }
            }
        ); 
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'L1m2\PlatformBundle\Entity\Extrait'
        ));
    }

    public function getName()
    {
        return 'l1m2_platformbundle_Extrait';
    }
}


