<?php

namespace App\Controller;

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
        $participant = new Participants();
        $nouvelleSortie = $this->createForm(CreerSortieType::class, $sorties);

        $nouvelleSortie->handleRequest($request);
        if($nouvelleSortie->isSubmitted() && $nouvelleSortie->isValid()){

            $repository = $this->getDoctrine()->getRepository(Participants::class);
            $participant=  $repository->findOneBy(["pseudo" => "Pierrot"]);


            $sorties->setOrganisateur($repository->findOneBy(["pseudo" => "Pierrot"]));

            $task = $nouvelleSortie->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute("Profil");
        }
        return $this->render('sortie/nouvelleSortie.html.twig', [
            'controller_name' => 'ProfilController',
            'nouvelleSortie' => $nouvelleSortie->createView()
        ]);
    }
}
