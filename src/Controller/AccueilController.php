<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    public function accueil()
    {
        return $this->render('accueil/accueil.html.twig', [
            'controller_name' => 'AccueilController',
        ]);
    }
}
