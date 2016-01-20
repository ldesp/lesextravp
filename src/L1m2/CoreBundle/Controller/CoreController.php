<?php

namespace L1m2\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class CoreController extends Controller
{
    // La page d'accueil
    public function indexAction()
    {
        // On retourne simplement la vue de la page d'accueil
        return $this->get('templating')->renderResponse('L1m2CoreBundle:Core:index.html.twig');
    }
    // La page de contact
    public function contactAction(Request $request)
    {
        // On retourne simplement la vue de la page de contact
        return $this->get('templating')->renderResponse('L1m2CoreBundle:Core:contact.html.twig');
    }

    // La page d'exemple
    public function exempleAction(Request $request)
    {
    $cols1 = '25,25,25,25,25';
    $mots1 = 'J    ,A    ,D    ,E    ';
    $pioc1 = 'EEEEIILMMRRSSTTX';
       // Enfin, on retourne  simplement vers la page d'exemple
       return $this->get('templating')->renderResponse('L1m2CoreBundle:Core:exemple.html.twig',array(
            'cols1' => $cols1,
            'mots1' => $mots1));
    }

    // La page d'admin
    public function adminAction()
    {
        // On retourne simplement la vue de la page d'admin
        return $this->get('templating')->renderResponse('L1m2CoreBundle:Core:admin.html.twig');
    }
}
