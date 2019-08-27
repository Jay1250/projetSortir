<?php

namespace App\Controller;

use App\Entity\Participants;
use App\Entity\Sites;
use App\Entity\Sorties;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Etats;

class AccueilController extends AbstractController
{
    public function accueil(Request $request)
    {
//        $listAdverts = $repository->findBy(
//            array('author' => 'Alexandre'), // Critere
//            array('date' => 'desc'),        // Tri
//            5,                              // Limite
//            0                               // Offset
//        );
        $repositorySortie = $this->getDoctrine()->getManager()->getRepository(Sorties::class);
//        $sorties = $repositorySortie->findBy(["etatsNoEtat" => Etats::Ouverte,"etatsNoEtat" => Etats::Cloturee, "etatsNoEtat" => Etats::Activite_en_cours]);
        $sorties = $repositorySortie->findAll();
        $repositorySites = $this->getDoctrine()->getManager()->getRepository(Sites::class);
        $Sites = $repositorySites->findAll();



        if($request->isMethod('post')) {

            $expression=null;
            $expressionBuilder = Criteria::expr();


//            $posts = $request->request->all();
            $formRecherche = $request->request->get("recherche");
            $formDateMin = $request->request->get("dateMin");
            $formDateMax = $request->request->get("dateMax");
            $formSite = $request->request->get("site");
            $formOrganisateur = $request->get("organisateur");
            $formInscrit = $request->request->get("inscrit");
            $formPasInscrit = $request->request->get("pasInscrit");
            $formPasser = $request->request->get("passer");


            $filtre = array();
            if ($formRecherche != "") {
//                $filtre[]=$criteria->expr()->contains('nom', $formRecherche);
                $filtre[] = $expressionBuilder->contains('nom', $formRecherche);

            }
            if ($formDateMin != "") {
                $filtre[] = $expressionBuilder->gte('datedebut', $formDateMin);
            }
            if ($formDateMax != "") {
                $filtre[] = $expressionBuilder->lte('datedebut', $formDateMax);
            }
            if ($formSite != "") {

                $filtre[] = $expressionBuilder->eq('sortiesNoSortie', $formSite);
            }
            $usr = $this->get('security.token_storage')->getToken()->getUser();

            if ($formOrganisateur != "") {
                $filtre[] = $expressionBuilder->eq('organisateur', $usr);
            } else {
                $filtre[] = $expressionBuilder->neq('organisateur', $usr);
            }

            if ($formInscrit != "") {
//                $filtre[] = $expressionBuilder->eq('organisateur', $usr);
//            } else {
//                $filtre[] = $expressionBuilder->neq('organisateur', $usr);
            }

            if ($formPasInscrit != "") {
//                $filtre[] = $expressionBuilder->eq('organisateur', $usr);
//            } else {
//                $filtre[] = $expressionBuilder->neq('organisateur', $usr);
            }

            if ($formPasser!=""){
                $filtre[] = $expressionBuilder->eq('etatsortie', $this->getDoctrine()->getManager()->getRepository(Sites::class)->findBy(["etatsNoEtat" => Etats::Passee]));
            }


//            $em = $this->getEntityManager();
//            $qb = $em->createQueryBuilder();
//
//            $q  = $qb->select(array('p'))
//                ->from('YourProductBundle:Product', 'p')
//                ->where(
//                    $qb->expr()->gt('p.price', $price)
//                )
//                ->orderBy('p.price', 'DESC')
//                ->getQuery();
//            foreach ($filtre as $fil){
//                $expression=$expressionBuilder->andX($expression,$fil);
//            }
            $taille=count($filtre);
            if($taille>0){
                $expression=$filtre[0];
                for ($index=1; $index<$taille; $index++){
                    $expression=$expressionBuilder->andX($expression,$filtre[$index]);
                }
            }

            $sorties=$repositorySortie->matching(new Criteria($expression));

            return $this->render('accueil/accueil.html.twig', [
                'controller_name' => 'AccueilController',
                'Sorties' => $sorties,
                'Sites' => $Sites,
            ]);
        }

        return $this->render('accueil/accueil.html.twig', [
            'controller_name' => 'AccueilController',
            'Sorties' => $sorties,
            'Sites' => $Sites,


        ]);



    }
}
