<?php

namespace App\Controller;

use App\Entity\Participants;
use App\Form\FormulaireAjouterProfilType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class ProfilController extends AbstractController
{
    public function profil()
    {
        return $this->render('profil/index.html.twig', [
            'controller_name' => 'ProfilController',
        ]);
    }
    public function nouveauProfil(Request $request)
    {
        $participants = new Participants();
        $nouveauProfil = $this->createForm(FormulaireAjouterProfilType::class, $participants);

        $nouveauProfil->handleRequest($request);
        if($nouveauProfil->isSubmitted() && $nouveauProfil->isValid()){
            //blabla

            return $this->redirectToRoute("Profil");
        }
        return $this->render('profil/nouveauProfil.html.twig', [
            'controller_name' => 'ProfilController',
            'nouveauProfil' => $nouveauProfil->createView()
        ]);
    }

    public function afficherProfil(Request $request){
        $participant = new Participants();
        $pseudoParticipant = $request->attributes->get('participant');
        $repository = $this->getDoctrine()->getRepository(Participants::class);
        if($participant= $repository->findOneBy(["pseudo" => $pseudoParticipant])){

        }
        else{
            return $this->redirectToRoute('Accueil');
        }
        return $this->render('profil/afficherProfil.html.twig', ['participant' => $participant]);
    }

    public function modifierProfil(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Participants::class);

        $usr= $this->get('security.token_storage')->getToken()->getUser();
        $pseudo=$usr->getUsername();

        if($participant= $repository->findOneBy(["pseudo" => $pseudo])){
            $participant = $this->createForm(FormulaireAjouterProfilType::class, $participant);
        }
        else{
            return $this->redirectToRoute('Accueil');
        }
        $participant->handleRequest($request);
        if($participant->isSubmitted() && $participant->isValid()){
            $task = $participant->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();
            return $this->redirectToRoute("AfficherProfil", array('participant' => $pseudo));
        }
        return $this->render('profil/modifierProfil.html.twig', array('nouveauProfil' => $participant->createView()));
    }
}
