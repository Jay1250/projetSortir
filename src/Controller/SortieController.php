<?php

namespace App\Controller;

use App\Entity\Etats;
use App\Entity\Lieux;
use App\Entity\Participants;
use App\Entity\Sorties;
use App\Form\CreerSortieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
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
        $repository = $this->getDoctrine()->getRepository(Lieux::class);
        $lieux = $repository->findAll();
        $sorties = new Sorties();
        $nouvelleSortie = $this->createForm(CreerSortieType::class, $sorties);
        $nouvelleSortie->handleRequest($request);
        if($nouvelleSortie->isSubmitted() && $nouvelleSortie->isValid() && $nouvelleSortie->getClickedButton()){
            $sorties->setOrganisateur($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $sorties->setEtatsNoEtat($entityManager->getReference(Etats::class,
                $nouvelleSortie->getClickedButton()->getName() === 'creer_et_ouvrir'? Etats::Ouverte: Etats::Cree));
            $entityManager->persist($sorties);
            $entityManager->flush();
            return $this->redirectToRoute("Accueil");
        }
        return $this->render('sortie/nouvelleSortie.html.twig', [
            'controller_name' => 'ProfilController',
            'nouvelleSortie' => $nouvelleSortie->createView(),
            'lieux' => $lieux
        ]);
    }

    /**
     * @Route("/ttoto2")
     */
    public function filtrerLieux(Request $request){
        $lieuId = $request->get('lieu');
        $repository = $this->getDoctrine()->getRepository(Lieux::class);
        $lieu = $repository->find($lieuId);

     //  $results = array('lieu' => "test");
     //   return new JsonResponse($results);
      //  $arr = array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5);

     //  $response = new Response(json_encode(["totooto"]));
      //  $response->headers->set('Content-Type', 'application/json');
       // return $response;
        return new JsonResponse($lieu);
    }


    /**
     * @Route("/ttoto", name="ttoto")
     */
    public function ttoto()
    {
        return new JsonResponse(['test']);
    }
}
