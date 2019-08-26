<?php

namespace App\Controller;

use App\Entity\Participants;
use App\Entity\Sites;
use App\Entity\Sorties;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Etats;

class AccueilController extends AbstractController
{
    public function accueil()
    {
        $repositorySortie = $this->getDoctrine()->getManager()->getRepository(Sorties::class);
//        $sorties = $repositorySortie->findBy(["etatsNoEtat" => Etats::Ouverte]);
        $sorties = $repositorySortie->findAll();
        $repositorySites = $this->getDoctrine()->getManager()->getRepository(Sites::class);
        $Sites = $repositorySites->findAll();

//        $allEtat = array();
//
//        foreach($Etats as $etat){
//            $allEtat[$etat->getNoEtat()] = $etat->getLibelle();
//        }

        return $this->render('accueil/accueil.html.twig', [
            'controller_name' => 'AccueilController',
            'Sorties' => $sorties,
            'Sites' => $Sites,


        ]);



    }
}
