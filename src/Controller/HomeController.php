<?php
	namespace App\Controller;

	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Annotation\Route;

	Class HomeController extends AbstractController {

		/**
		 * @Route ("/bonjour/{prenom}/age/{age}", name="hello")
		 * @Route ("/bonjour", name="hello_base")
		 * @Route ("/bonjour/{prenom}", name="hello_prenom")
		 * Montre la page qui dit bonjour
		 */
		public function hello($prenom = "test", $age = 0){
			return $this->render(
				"hello.html.twig",
				[
					"prenom" => $prenom,
					"age" => $age
				]
			);
		}
		/**
		 * @Route("/", name="homepage")
		 */
		public function home() {
			$prenoms = ["Lior" => 31, "Joseph" => 12, "Anne" => 3];
			return $this->render(
				"home.html.twig",
				[
					"title" => "Au revoir tout le monde",
					"age" => 3,
					"tableau" => $prenoms,
				]
			);
		}
	}