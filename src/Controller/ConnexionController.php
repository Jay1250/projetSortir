<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ConnexionController extends AbstractController
{
    public function connexion()
    {
        return $this->render('connexion/index.html.twig', [
            'controller_name' => 'ConnexionController',
        ]);
    }
}
