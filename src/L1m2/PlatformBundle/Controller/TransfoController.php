<?php
// src/L1m2/PlatformBundle/Controller/TranfoController.php

namespace L1m2\PlatformBundle\Controller;

use L1m2\PlatformBundle\Form\TransfoType;
use L1m2\PlatformBundle\Entity\Transfo;
use L1m2\PlatformBundle\Entity\Extrait;
use L1m2\PlatformBundle\Form\PropoType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class TransfoController extends Controller
{
/**
 * @ParamConverter("extrait", options={"mapping":{"extrait_id":"id"}})
 *
 */    
    public function transformerAction(Request $request, Extrait $extrait)
    {
        // On crée un objet Transfo
        $transfo = new Transfo();
        // on génère le formulaire
        $form = $this->get('form.factory')->create(new TransfoType, $transfo);
        if ($form->handleRequest($request)->isValid())
        {
            if ($transfo->verifierMots($extrait->getExtraitFiltre()))
            {   
                $em = $this->getDoctrine()->getManager();
                $listTransfos = $em
                    ->getRepository('L1m2PlatformBundle:Transfo')
                    ->findByExtrait($id)
                ;
                if (count($listTransfos) > 0) 
                {
                    $troproche = $transfo->comparerMots($listTransfos, strlen($extrait->getPioche()) >> 1); 
                    if ($troproche != null)
                    {
                        $request->getSession()->getFlashBag()->add('notice', 'Votre transformation se rapproche trop de la liste: '.$troproche->getMots()) ;
                        return $this->redirect($this->generateUrl('l1m2_platform_initiales'));
                    }
                }  
                // On modifie la base de donnees
                $transfo->setExtrait($extrait);
                $em->persist($transfo);
                $em->flush();
                $request->getSession()->getFlashBag()->add('notice', 'Votre transformation a été enregistrée.') ;
                // On redirige vers la liste
                return $this->redirect($this->generateUrl('l1m2_platform_initiales'));
            }
            else
            {
                throw $this->createNotFoundException("Il y a un probleme");
            }
        }
        return $this->render('L1m2PlatformBundle:Transfo:transformer.html.twig', array(
            'form'    => $form->createView(),
            'id'      => $extrait->getId(),
            'colo1'   => $extrait->getInitiales(),
            'pioche'  => $extrait->getPioche() 
        ));
    }

    public function listeranagrammesAction($page)
    {
        if ($page < 1) 
        {
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }

        // il faudrait utiliser un paramètre, et y accéder via $this->container->getParameter('nb_per_page')
        $nbPerPage = 7;


        // On récupère notre objet Paginator
        $listTransfos = $this->getDoctrine()
            ->getManager()
            ->getRepository('L1m2PlatformBundle:Transfo')
            ->getPagedAcceptees($page, $nbPerPage)
        ;

        // On calcule le nombre total de pages grâce au count($listTransfos) qui retourne le nombre total de transfos
        $nbPages = ceil(count($listTransfos)/$nbPerPage);

        // Si la page n'existe pas, on retourne une 404
        if ($page > $nbPages) 
        {
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }
        // On donne toutes les informations nécessaires à la vue
        return $this->render('L1m2PlatformBundle:Transfo:listeranagrammes.html.twig', array(
            'listTransfos' => $listTransfos,
            'nbPages'     => $nbPages,
            'page'        => $page
        ));
    }

/**
 * @ParamConverter("transfo", options={"mapping":{"transfo_id":"id"}})
 *
 */  
    public function retrouverAction(Request $request, Transfo $transfo)
    {
        return $this->render('L1m2PlatformBundle:Transfo:retrouver.html.twig', array(
            'transfo' => $transfo
        ));
    }

/*
 * actions reservees à ADMIN
 *
 */
    public function listertransfosAction(Request $request, $page)
    {
        if ($page < 1) 
        {
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }

        // il faudrait utiliser un paramètre, et y accéder via $this->container->getParameter('nb_per_page')
        $nbPerPage = 7;

        // On récupère notre objet Paginator
        $listTransfos = $this->getDoctrine()
            ->getManager()
            ->getRepository('L1m2PlatformBundle:Transfo')
            ->getPagedRecues($page, $nbPerPage)
        ;

        // On calcule le nombre total de pages grâce au count($listTransfos) qui retourne le nombre total de transfos
        $nbPages = ceil(count($listTransfos)/$nbPerPage);
        if ($nbPages == 0) 
        {
            $request->getSession()->getFlashBag()->add('notice', 'pas de transfos à valider') ;
            return $this->render('L1m2CoreBundle:Core:admin.html.twig');
        }

        // Si la page n'existe pas, on retourne une 404
        if ($page > $nbPages) 
        {
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }

        // On donne toutes les informations nécessaires à la vue
        return $this->render('L1m2PlatformBundle:Transfo:listertransfos.html.twig', array(
            'listTransfos' => $listTransfos,
            'nbPages'     => $nbPages,
            'page'        => $page
        ));
    }
/**
 * @ParamConverter("transfo", options={"mapping":{"transfo_id":"id"}})
 *
 */    
    public function validermotsAction(Request $request, Transfo $transfo)
    {
        // on verifie que l'objet n'a pas deja ete validé (renvoi du formulaire)
        if ($transfo->isAccepted() or $transfo->isRejected())
        {
            return $this->redirect($this->generateUrl('l1m2_platform_transfos'));
        }
        // Et on construit le formulaire avec les mots proposes
        $form = $this->get('form.factory')->create(new PropoType, $transfo);
        if  ($form->handleRequest($request)->isValid())
        {
           $em = $this->getDoctrine()->getManager();
           // On met à jour les donnees pour table transfo (status,date)
           $transfo->setDateParu(new \Datetime());
           $em->persist($transfo);           
           if ($transfo->isAccepted())
           {
               // On incremente le nombre de transfos acceptees pour l'extrait
               $extrait = $transfo->getExtrait();
               $extrait->increaseNtransfo();
               $em->persist($extrait);
           }
           $em->flush();
           $request->getSession()->getFlashBag()->add('notice', ' la liste de mots a été vérifiée, status:'.$transfo->getStatus()) ;
           // On redirige vers la liste
           return $this->redirect($this->generateUrl('l1m2_platform_transfos'));
        }
        return $this->render('L1m2PlatformBundle:Transfo:validermots.html.twig', array(
            'form' => $form->createView(),
            'listMots' => explode(',', $transfo->getMots()),
            'auteur' => $transfo->getAuteur()
        ));
    }
 



}
