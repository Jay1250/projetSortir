<?php

namespace App\Controller;

use App\Entity\Etats;
use App\Entity\Participants;
use App\Entity\Sorties;
use App\Form\CreerSortieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    public function sortie()
    {
        return $this->render('sortie/index.html.twig', [
            'controller_name' => 'SortieController',
        ]);
    }

    public function nouvelleSortie(Request $request)
    {
        $sorties = new Sorties();
        $nouvelleSortie = $this->createForm(CreerSortieType::class, $sorties);
        $nouvelleSortie->handleRequest($request);
        if($nouvelleSortie->isSubmitted() && $nouvelleSortie->isValid() && $nouvelleSortie->getClickedButton()){
            $sorties->setOrganisateur($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $sorties->setEtatsNoEtat($entityManager->getReference(Etats::class,
                $nouvelleSortie->getClickedButton()->getName() === 'creer_et_ouvrir'? Etats::Ouverte: Etats::Cree));
            $entityManager->persist($sorties);
            $entityManager->flush();
            return $this->redirectToRoute("Accueil");
        }
        return $this->render('sortie/nouvelleSortie.html.twig', [
            'controller_name' => 'ProfilController',
            'nouvelleSortie' => $nouvelleSortie->createView()
        ]);
    }
}
