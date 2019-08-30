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
        $formRecherche = "";
        $formDateMin = "";
        $formDateMax = "";
        $formSite = $numSite;
        $formOrganisateur = "1";
        $formInscrit = "1";
        $formPasInscrit = "1";
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

        $usr = $this->get('security.token_storage')->getToken()->getUser();
        $usrId= $usr->getNoParticipant();

        $em=$this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        /** @var QueryBuilder $qb */
        $qb ->select('s')
            ->from(Sorties::class,'s')
            ;
        if ( $formRecherche != "" or $formDateMin != "" or $formDateMax != "" or $formOrganisateur != "" or $formInscrit != "" or $formPasInscrit != "" or $formPasser != "") {
            if ($formOrganisateur != "") {
                $qb->orWhere($qb->expr()->eq('s.organisateur', $qb->expr()->literal($usrId)));
            } else {
//                $qb->andWhere($qb->expr()->neq('s.organisateur', $qb->expr()->literal($usrId)));
            }

            if ($formInscrit != "" and $formPasInscrit != "") {
                $qb->leftJoin('s.participantsNoParticipant', 'p');
                $qb->orWhere($qb->expr()->orX(
                    $qb->expr()->eq('p', $qb->expr()->literal($usrId)),
                    $qb->expr()->orX(
                        $qb->expr()->neq('p', $qb->expr()->literal($usrId)),
                        $qb->expr()->isNull('p')
                    )
                ));
            } elseif ($formInscrit != "") {

                $qb->leftJoin('s.participantsNoParticipant', 'p');
                $qb->orWhere($qb->expr()->eq('p', $qb->expr()->literal($usrId)));

            } elseif ($formPasInscrit != "") {
                $qb->leftJoin('s.participantsNoParticipant', 'p');
                $qb->orWhere($qb->expr()->orX($qb->expr()->neq('p', $qb->expr()->literal($usrId)), $qb->expr()->isNull('p')));
            }


            if ($formPasser != "") {
                $qb->orWhere($qb->expr()->eq('s.etatsNoEtat', $qb->expr()->literal(Etats::Passee)));
            } else {
                $qb->andWhere($qb->expr()->neq('s.etatsNoEtat', $qb->expr()->literal(Etats::Passee)));
            }

            if ($formDateMin != "") {
                $qb->andWhere($qb->expr()->gte('s.datedebut', $qb->expr()->literal($formDateMin)));
            }
            if ($formDateMax != "") {
                $qb->andWhere($qb->expr()->lte('s.datedebut', $qb->expr()->literal($formDateMax)));
            }
            if ($formSite != "") {
                $qb->andWhere($qb->expr()->eq('s.sortiesNoSortie', $qb->expr()->literal($formSite)));
            }
            $qb->andWhere($qb->expr()->like('s.nom', $qb->expr()->literal('%' . $formRecherche . '%')));
        }

        $qb->andWhere($qb->expr()->eq('s.etatsortie', $qb->expr()->literal(1)));



        $query = $qb->getQuery();
        $sorties=$query->getResult();

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
