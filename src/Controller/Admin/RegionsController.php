<?php

namespace App\Controller\Admin;

use App\Entity\Regions;
use App\Form\RegionsType;
use App\Repository\RegionsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/regions", name="admin_regions_")
 * @package App\Controller\Admin
 */
class RegionsController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(RegionsRepository $regionsRepo)
    {
        return $this->render('admin/regions/index.html.twig', [
            'regions' => $regionsRepo->findAll()
        ]);
    }

    /**
     * @Route("/ajout", name="ajout")
     */
    public function ajoutRegion(Request $request)
    {
        $region = new Regions;

        $form = $this->createForm(RegionsType::class, $region);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($region);
            $em->flush();

            return $this->redirectToRoute('admin_regions_home');
        }

        return $this->render('admin/regions/ajout.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
