<?php
// src/L1m2/PlatformBundle/Controller/ExtraitController.php

namespace L1m2\PlatformBundle\Controller;

use L1m2\PlatformBundle\Form\ExtraitType;
use L1m2\PlatformBundle\Entity\Extrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ExtraitController extends Controller
{

    public function listerinitialesAction($page, $order)
    {
        if ($page < 1) 
        {
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }
        
        $page_in = $page;
        
        if ($order < 1 || $order > 4) 
        {
            throw $this->createNotFoundException("Le tri ".$order." n'existe pas.");
        }
        
        // verifier si le tri change et le mettre à jour
        $session = $this->getRequest()->getSession();
        
        if($session->has('orderinitiales'))
        {
             if ($order != $session->get('orderinitiales'))
             {
                 $session->set('orderinitiales', $order);
                 $page_in = 1;
             }                       
        } else {
            $session->set('orderinitiales', $order);
            $page_in = 1;
        }

        // il faudrait utiliser un paramètre, et y accéder via $this->container->getParameter('nb_per_page')
        $nbPerPage = 7;


        // On récupère notre objet Paginator
        $listExtraits = $this->getDoctrine()
            ->getManager()
            ->getRepository('L1m2PlatformBundle:Extrait')
            ->getPagedAcceptees($page_in, $nbPerPage, $order)
        ;

        // On calcule le nombre total de pages grâce au count($listExtraits) qui retourne le nombre total d'extraits
        $nbPages = ceil(count($listExtraits)/$nbPerPage);

        // Si la page n'existe pas, on retourne une 404
        if ($page_in > $nbPages) 
        {
            throw $this->createNotFoundException("La page ".$page_in." n'existe pas.");
        }
        // On donne toutes les informations nécessaires à la vue
        return $this->render('L1m2PlatformBundle:Extrait:listerinitiales.html.twig', array(
            'listExtraits' => $listExtraits,
            'nbPages'     => $nbPages,
            'page'        => $page_in,
            'order'       => $order
        ));
    }

/*
 * actions reservees à ADMIN
 *
 */ 
    public function proposerAction(Request $request)
    {
        // On crée un objet Extrait
        $extrait = new Extrait();
        // on génère le formulaire
        $form = $this->get('form.factory')->create(new ExtraitType, $extrait);
        // On vérifie que les valeurs entrées sont correctes
        if  ($form->handleRequest($request)->isValid())
        {
            // On enregistre notre objet $Extrait dans la base de donnée
            $em = $this->getDoctrine()->getManager();
            $em->persist($extrait);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', 'Proposition enregistrée');
            // On redirige vers la page d'accueil
            return $this->redirect($this->generateUrl('l1m2_core_admin'));
        }
        // À ce stade, le formulaire n'est pas valide car :
        // - Soit la requête est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
        // - Soit la requête est de type POST, mais le formulaire contient des valeurs invalides, donc on l'affiche de nouveau
        return $this->render('L1m2PlatformBundle:Extrait:proposer.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function listerextraitsAction($page)
    {
        if ($page < 1) 
        {
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }
        
        // il faudrait utiliser un paramètre, et y accéder via $this->container->getParameter('nb_per_page')
        $nbPerPage = 7;

        // On récupère notre objet Paginator
        $listExtraits = $this->getDoctrine()
            ->getManager()
            ->getRepository('L1m2PlatformBundle:Extrait')
            ->getPagedToutes($page, $nbPerPage)
        ;

        // On calcule le nombre total de pages grâce au count($listExtraits) qui retourne le nombre total d'extraits
        $nbPages = ceil(count($listExtraits)/$nbPerPage);

        // Si la page n'existe pas, on retourne une 404
        if ($page > $nbPages) 
        {
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }
        // On donne toutes les informations nécessaires à la vue
        return $this->render('L1m2PlatformBundle:Extrait:listerextraits.html.twig', array(
            'listExtraits' => $listExtraits,
            'nbPages'     => $nbPages,
            'page'        => $page
        ));
    }
}
