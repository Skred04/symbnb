<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Repository\AdRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
	 * @return Response
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
    public function show(Ad $ad): Response {
		return $this->render("ad/show.html.twig", [
			"ad" => $ad
		]);
	}
}
