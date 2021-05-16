<?php

namespace App\Controller;

use App\Repository\AdRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repo): Response
    {
    	$ads = $repo->findAll();

        return $this->render('ad/index.html.twig', [
            'ads' => $ads,
        ]);
    }

	/**
	 * Permet d'afficher une seule annonce
	 *
	 * @Route("/ads/{slug}", name="ads_show")
	 * @return Response
	 * @author Pier
	 */
    public function show($slug, AdRepository $repo): Response {
    	$ad = $repo->findOneBySlug($slug);

		return $this->render("ad/show.html.twig", [
			"ad" => $ad
		]);
	}
}
