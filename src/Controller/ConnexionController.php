<?php

namespace App\Controller;

use App\Entity\Participants;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;


class ConnexionController extends AbstractController
{
    public function connexion()
    {
        $participant = new Participants();

        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $participant);

        $formBuilder
            ->add('pseudo', TextType::class)
            ->add('motDePasse', TextType::class);

        $form = $formBuilder->getForm();

        return $this->render('connexion/index.html.twig', array('form' => $form->createView(),
        ));
    }
}

