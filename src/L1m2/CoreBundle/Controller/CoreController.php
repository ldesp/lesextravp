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
        // On récupère la session depuis la requête, en argument du contrôleur
        $session = $request->getSession();
        // Et on définit notre message
        $session->getFlashBag()->add('notice', 'La page de contact n’est pas encore disponible, merci de revenir plus tard.');
        // Enfin, on redirige simplement vers la page d'accueil
        return new RedirectResponse($this->get('router')->generate('l1m2_core_home'));
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
