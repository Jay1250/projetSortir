<?php

namespace App\Controller;

use App\Entity\Participants;
use App\Form\FormulaireConnexionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class ConnexionController extends AbstractController
{
    public function connexion(Request $request, AuthenticationUtils $authenticationUtils)
    {
        $uneVariable = new Participants();
        $participant = new Participants();
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        $connexion = $this->createForm(FormulaireConnexionType::class, $participant);


        return $this->render('connexion/connexion.html.twig', array('form' => $connexion->createView(),
            'last_username' => $lastUsername,
            'error' => $error,
        ));
    }
}

