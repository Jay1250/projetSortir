<?php

namespace App\Controller;

use App\Entity\Participants;
use App\Form\FormulaireAjouterProfilType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\validator\Constraints as Assert;

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
        if($nouveauProfil->isSubmitted()){
            //blabla

            return $this->redirectToRoute("Profil");
        }
        return $this->render('profil/nouveauProfil.html.twig', [
            'controller_name' => 'ProfilController',
            'nouveauProfil' => $nouveauProfil->createView()
        ]);
    }

}
