<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    public function sortie()
    {
        return $this->render('sortie/connexion.html.twig', [
            'controller_name' => 'SortieController',
        ]);
    }
}
