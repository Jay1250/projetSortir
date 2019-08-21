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
    public function connexion(AuthenticationUtils $authenticationUtils)
    {
//        if ($this->getUser()) {
//            $this->redirectToRoute('Accueil');
//        }

        $participant = new Participants();

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        $connexion = $this->createForm(FormulaireConnexionType::class, $participant);


        return $this->render('connexion/connexion.html.twig', array('form' => $connexion->createView(),
            'last_username' => $lastUsername,
            'error' => $error,
        ));
    }
    public function deconnexion()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }
}

