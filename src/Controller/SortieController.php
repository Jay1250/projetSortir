<?php

namespace App\Controller;

use App\Entity\Etats;
use App\Entity\Lieux;
use App\Entity\Participants;
use App\Entity\Sorties;
use App\Entity\Villes;
use App\Form\CreerSortieType;
use JMS\Serializer\SerializerBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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
        // recup lieux
        $repository = $this->getDoctrine()->getRepository(Lieux::class);
        $lieux = $repository->findAll();
        // recup villes
        $repository = $this->getDoctrine()->getRepository(Villes::class);
        $villes = $repository->findAll();
        $sorties = new Sorties();
        $nouvelleSortie = $this->createForm(CreerSortieType::class, $sorties);
        $nouvelleSortie->handleRequest($request);
        if($nouvelleSortie->isSubmitted() && $nouvelleSortie->isValid() && $nouvelleSortie->getClickedButton()){
            $sorties->setOrganisateur($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $sorties->setEtatsNoEtat($entityManager->getReference(Etats::class,
                $nouvelleSortie->getClickedButton()->getName() === 'creer_et_ouvrir'? Etats::Ouverte: Etats::Creee));
            /** @var UploadedFile $profilPhotoFile */
            $profilPhotoFile = $nouvelleSortie['photoSortie']->getData();
            if ($profilPhotoFile) {
                $originalFilename = pathinfo($profilPhotoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$profilPhotoFile->guessExtension();
                try {
                    $profilPhotoFile->move(
                        $this->getParameter('photos_sortie_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $sorties->setUrlphoto($newFilename);
            }
            $entityManager->persist($sorties);
            $entityManager->flush();
            return $this->redirectToRoute("Accueil");
        }
        return $this->render('sortie/nouvelleSortie.html.twig', [
            'controller_name' => 'ProfilController',
            'nouvelleSortie' => $nouvelleSortie->createView(),
            'lieux' => $lieux,
            'villes' =>$villes,
            'isModif'=>false
        ]);
    }

    public function modifierSortie(Request $request)
    {
        // recup info sortie
        $noSortie = $request->attributes->get('noSortie');
        $repository = $this->getDoctrine()->getRepository(Sorties::class);
        $sorties = $repository->find($noSortie);
        // recup lieux
        $repository = $this->getDoctrine()->getRepository(Lieux::class);
        $lieux = $repository->findAll();
        // recup villes
        $repository = $this->getDoctrine()->getRepository(Villes::class);
        $villes = $repository->findAll();
        $nouvelleSortie = $this->createForm(CreerSortieType::class, $sorties);
        $nouvelleSortie->handleRequest($request);
        if($nouvelleSortie->isSubmitted() && $nouvelleSortie->isValid() && $nouvelleSortie->getClickedButton()){
            $sorties->setOrganisateur($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $sorties->setEtatsNoEtat($entityManager->getReference(Etats::class,
                $nouvelleSortie->getClickedButton()->getName() === 'creer_et_ouvrir'? Etats::Ouverte: Etats::Creee));
            /** @var UploadedFile $profilPhotoFile */
            $profilPhotoFile = $nouvelleSortie['photoSortie']->getData();
            if ($profilPhotoFile) {
                $originalFilename = pathinfo($profilPhotoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$profilPhotoFile->guessExtension();
                try {
                    $profilPhotoFile->move(
                        $this->getParameter('photos_sortie_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $sorties->setUrlphoto($newFilename);
            }
            $entityManager->persist($sorties);
            $entityManager->flush();
            return $this->redirectToRoute("Accueil");
        }
        return $this->render('sortie/nouvelleSortie.html.twig', [
            'controller_name' => 'ProfilController',
            'nouvelleSortie' => $nouvelleSortie->createView(),
            'lieux' => $lieux,
            'villes' =>$villes,
            'isModif'=>true
        ]);
    }

    public function afficherSortie(Request $request)
    {
        $noSortie = $request->attributes->get('noSortie');
        $repository = $this->getDoctrine()->getRepository(Sorties::class);
        $sortie = $repository->find($noSortie);
        return $this->render('sortie/afficherSortie.html.twig', ['sortie' =>$sortie]);
    }

    public function filtrerLieux(Request $request){
        $lieuId = $request->get('lieu');
        $repository = $this->getDoctrine()->getRepository(Lieux::class);
        $lieu = $repository->find($lieuId);
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $productSerialized = $serializer->serialize($lieu, 'json');
        return new JsonResponse($productSerialized);
    }

    public function getLieuxByVille(Request $request){
        if($villeId = $request->get('ville')){
            $repository = $this->getDoctrine()->getRepository(Villes::class);
            $ville = $repository->find($villeId);
            $repository = $this->getDoctrine()->getRepository(Lieux::class);
            $lieux = $repository->findBy(array('villesNoVille' => $ville));
         }
         else{
             $repository = $this->getDoctrine()->getRepository(Lieux::class);
             $lieux = $repository->findAll();
        }
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $productSerialized = $serializer->serialize($lieux, 'json');
        return new JsonResponse($productSerialized);
    }

    public function inscrire(Request $request){
        $numSortie = $request->attributes->get('noSortie');
        $repositorySortie = $this->getDoctrine()->getManager()->getRepository(Sorties::class);
        $sortie=$repositorySortie->findOneBy(["noSortie" => $numSortie ]);
        $this->getUser()->addSortiesNoSortie($sortie);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($sortie);
        $entityManager->flush();
        return $this->redirectToRoute("Accueil");

    }

    public function deinscrire(Request $request){
        $numSortie = $request->attributes->get('noSortie');
        $repositorySortie = $this->getDoctrine()->getManager()->getRepository(Sorties::class);
        $sortie=$repositorySortie->findOneBy(["noSortie" => $numSortie ]);
        $this->getUser()->removeSortiesNoSortie($sortie);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($sortie);
        $entityManager->flush();
        return $this->redirectToRoute("Accueil");

    }
}
