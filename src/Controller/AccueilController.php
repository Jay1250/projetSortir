<?php

namespace App\Controller;

use App\Entity\Participants;
use App\Entity\Sites;
use App\Entity\Sorties;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Etats;



class AccueilController extends AbstractController
{
    public function accueil(Request $request)
    {
        // Procedure stockée
        /** @var Connection $conn */
        $conn =$this->getDoctrine()->getConnection();
        $query = $conn->prepare('Call update_sorties_etat ()');
        $query->execute();

        $numSite=$this->getUser()->getSitesNoSite()->getNoSite();
        dump($numSite);
        $formRecherche = "";
        $formDateMin = "";
        $formDateMax = "";
        $formSite = $numSite;
        $formOrganisateur = "";
        $formInscrit = "";
        $formPasInscrit = "";
        $formPasser = "";

        $repositorySortie = $this->getDoctrine()->getManager()->getRepository(Sorties::class);
//        $sorties = $repositorySortie->findBy(["etatsNoEtat" => Etats::Ouverte,"etatsNoEtat" => Etats::Cloturee, "etatsNoEtat" => Etats::Activite_en_cours]);
        $sorties = $repositorySortie->findAll();
        $repositorySites = $this->getDoctrine()->getManager()->getRepository(Sites::class);
        $Sites = $repositorySites->findAll();


        if($request->isMethod('post')) {

//            $posts = $request->request->all();
            $formRecherche = $request->request->get("recherche");
            $formDateMin = $request->request->get("dateMin");
            $formDateMax = $request->request->get("dateMax");
            $formSite = $request->request->get("site");
            $formOrganisateur = $request->get("organisateur");
            $formInscrit = $request->request->get("inscrit");
            $formPasInscrit = $request->request->get("pasInscrit");
            $formPasser = $request->request->get("passer");
        }


        $em=$this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        /** @var QueryBuilder $qb */
        $qb ->select('s')
            ->from(Sorties::class,'s')
            ;

        $qb->where($qb->expr()->like('s.nom', $qb->expr()->literal($formRecherche.'%')));

        if ($formDateMin != "") {
//                $filtre[] = $expressionBuilder->gte('datedebut', new \DateTime($formDateMin));
            $qb->andWhere($qb->expr()->gte('s.datedebut', $qb->expr()->literal($formDateMin)));
        }
        if ($formDateMax != "") {
//                $filtre[] = $expressionBuilder->lte('datedebut', new \DateTime($formDateMax));
            $qb->andWhere($qb->expr()->lte('s.datedebut', $qb->expr()->literal($formDateMax)));
        }
        if ($formSite != "") {
//                $filtre[] = $expressionBuilder->eq('sortiesNoSortie', $this->getDoctrine()->getManager()->getRepository(Sites::class)->findOneBy(["nomSite" =>$formSite]));
            $qb->andWhere($qb->expr()->eq('s.sortiesNoSortie', $qb->expr()->literal($formSite)));
        }

        $usr = $this->get('security.token_storage')->getToken()->getUser();
        $usrId= $usr->getNoParticipant();
        if ($formOrganisateur != "") {
//                $filtre[] = $expressionBuilder->eq('organisateur', $usr);
            $qb->andWhere($qb->expr()->eq('s.organisateur', $qb->expr()->literal($usrId)));
        } else {
//                $filtre[] = $expressionBuilder->neq('organisateur', $usr);
            $qb->andWhere($qb->expr()->neq('s.organisateur', $qb->expr()->literal($usrId)));
        }

        if ($formPasser!=""){
//                $filtre[] = $expressionBuilder->eq('etatsortie', Etats::Passee);
            $qb->andWhere($qb->expr()->eq('s.etatsNoEtat', $qb->expr()->literal(Etats::Passee)));
        } else {
//
            $qb->andWhere($qb->expr()->neq('s.etatsNoEtat', $qb->expr()->literal(Etats::Passee)));
        }



        if ($formInscrit != "" and $formPasInscrit != "") {

        } elseif($formInscrit != "") {

            $qb->leftJoin('s.participantsNoParticipant', 'p');
            $qb->andWhere($qb->expr()->eq('p', $qb->expr()->literal($usrId)));

        }elseif ($formPasInscrit != ""){
            $qb->leftJoin('s.participantsNoParticipant', 'p');
            $qb->andWhere($qb->expr()->orX($qb->expr()->neq('p', $qb->expr()->literal($usrId)),$qb->expr()->isNull('p')));
        }


        $query = $qb->getQuery();
        $sorties=$query->getResult();
//            dump($sorties);

//            $sorties=$repositorySortie->matching(new Criteria($expression));

//            return $this->render('accueil/accueil.html.twig', [
//                'controller_name' => 'AccueilController',
//                'Sorties' => $sorties,
//                'Sites' => $Sites,
//            ]);


        return $this->render('accueil/accueil.html.twig', [
            'controller_name' => 'AccueilController',
            'Sorties' => $sorties,
            'Sites' => $Sites,
            'formRecherche'=>$formRecherche,
            'formDateMin'=>$formDateMin,
            'formDateMax'=>$formDateMax,
            'formSite'=>$formSite,
            'formOrganisateur'=>$formOrganisateur,
            'formInscrit'=>$formInscrit,
            'formPasInscrit'=>$formPasInscrit,
            'formPasser'=>$formPasser,

        ]);

    }
}

//SELECT
//    s0_.no_sortie AS no_sortie_0,
//    s0_.nom AS nom_1,
//    s0_.datedebut AS datedebut_2,
//    s0_.duree AS duree_3,
//    s0_.datecloture AS datecloture_4,
//    s0_.nbinscriptionsmax AS nbinscriptionsmax_5,
//    s0_.descriptioninfos AS descriptioninfos_6,
//    s0_.etatsortie AS etatsortie_7,
//    s0_.urlPhoto AS urlPhoto_8,
//    s0_.etats_no_etat AS etats_no_etat_9,
//    s0_.lieux_no_lieu AS lieux_no_lieu_10,
//    s0_.organisateur AS organisateur_11,
//    s0_.sorties_no_sortie AS sorties_no_sortie_12
//FROM
//    sorties s0_
//LEFT JOIN inscriptions i2_ ON
//    s0_.no_sortie = i2_.sorties_no_sortie
//LEFT JOIN participants p1_ ON
//    p1_.no_participant = i2_.participants_no_participant
//WHERE
//    s0_.nom LIKE '%' AND s0_.sorties_no_sortie = '1' AND s0_.organisateur <> 3 AND s0_.etats_no_etat <> 5 AND (p1_.no_participant <> '3' OR p1_.no_participant IS null)