<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Repository\AnnoncesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/annonces", name="annonces_")
 * @package App\Controller
 */
class AnnoncesController extends AbstractController
{
    /**
     * @Route("/details/{slug}", name="details")
     */
    public function details($slug, AnnoncesRepository $annoncesRepo)
    {
        $annonce = $annoncesRepo->findOneBy(['slug' => $slug]);

        if(!$annonce){
            throw new NotFoundHttpException('Pas d\'annonce trouvée');
        }

        return $this->render('annonces/details.html.twig', compact('annonce'));
    }

    /**
     * @Route("/favoris/ajout/{id}", name="ajout_favoris")
     */
    public function ajoutFavoris(Annonces $annonce)
    {
        if(!$annonce){
            throw new NotFoundHttpException('Pas d\'annonce trouvée');
        }
        $annonce->addFavori($this->getUser());

        $em = $this->getDoctrine()->getManager();
        $em->persist($annonce);
        $em->flush();
        return $this->redirectToRoute('app_home');
    }

    /**
     * @Route("/favoris/retrait/{id}", name="retrait_favoris")
     */
    public function retraitFavoris(Annonces $annonce)
    {
        if(!$annonce){
            throw new NotFoundHttpException('Pas d\'annonce trouvée');
        }
        $annonce->removeFavori($this->getUser());

        $em = $this->getDoctrine()->getManager();
        $em->persist($annonce);
        $em->flush();
        return $this->redirectToRoute('app_home');
    }
}
