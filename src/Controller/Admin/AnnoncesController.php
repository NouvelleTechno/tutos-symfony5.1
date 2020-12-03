<?php

namespace App\Controller\Admin;

use App\Entity\Annonces;
use App\Repository\AnnoncesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/annonces", name="admin_annonces_")
 * @package App\Controller\Admin
 */
class AnnoncesController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(AnnoncesRepository $annoncesRepo)
    {
        return $this->render('admin/annonces/index.html.twig', [
            'annonces' => $annoncesRepo->findAll()
        ]);
    }

    /**
     * @Route("/activer/{id}", name="activer")
     */
    public function activer(Annonces $annonce)
    {
        $annonce->setActive(($annonce->getActive())?false:true);

        $em = $this->getDoctrine()->getManager();
        $em->persist($annonce);
        $em->flush();

        return new Response("true");
    }

    /**
     * @Route("/supprimer/{id}", name="supprimer")
     */
    public function supprimer(Annonces $annonce)
    {
        $images = $annonce->getImages();
        
        if($images){
            // On boucle sur les images de l'annonce
            foreach($images as $image){
                // On "génère" le chemin physique de l'image
                $nomImage = $this->getParameter("images_directory") . '/' . $image->getName();
                
                // On vérifie si l'image existe
                if(file_exists($nomImage)){
                    unlink($nomImage);
                }
            }
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($annonce);
        $em->flush();

        $this->addFlash('message', 'Annonce supprimée avec succès');
        return $this->redirectToRoute('admin_annonces_home');
    }
}
