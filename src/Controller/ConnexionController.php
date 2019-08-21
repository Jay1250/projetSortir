<?php

namespace App\Controller;

use App\Entity\Participants;
use App\Form\FormulaireConnexionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;


class ConnexionController extends AbstractController
{
    public function connexion(Request $request)
    {
        $uneVariable = new Participants();
        $participant = new Participants();
        $connexion = $this->createForm(FormulaireConnexionType::class, $participant);

        $connexion->handleRequest($request);
        if($connexion->isSubmitted()){
            //blabla
            $uneVariable->findOneBy(["pseudo" => "jorge"]);


            return $this->redirectToRoute("Accueil");
        }

        return $this->render('connexion/connexion.html.twig', array('form' => $connexion->createView(),
        ));
    }
}

