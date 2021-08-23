<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Repository\BookingRepository;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminBookingController extends AbstractController
{
    /**
     * Permet d'afficher la liste des réservations
     *
     * @Route("/admin/bookings/{page<\d+>?1}", name="admin_bookings_index")
     */
    public function index($page, PaginationService $pagination): Response
    {
        $pagination->setEntityClass(Booking::class)
                    ->setPage($page);

        return $this->render('admin/booking/index.html.twig', [
            "pagination" => $pagination
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'edition d'une réservation
     *
     * @Route ("/admin/bookings/{id}/edit", name="admin_bookings_edit")
     *
     * @param Booking $booking
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function edit(Booking $booking, Request $request, EntityManagerInterface $manager) : Response {
        $form = $this->createForm(AdminBookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $booking->setAmount(0);
            $manager->persist($booking);
            $manager->flush();

            $this->addFlash("success", "La réservation n°{$booking->getId()} à bien été modifiée");

            return $this->redirectToRoute("admin_bookings_index");
        }

        return $this->render("admin/booking/edit.html.twig", [
            "booking" => $booking,
            "form" => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer une réservation
     *
     * @Route ("/admin/bookings/{id}/delete", name="admin_bookings_delete")
     * @return Response
     */
    public function delete(Booking $booking, EntityManagerInterface $manager): Response {
        $manager->remove($booking);
        $manager->flush();

        $this->addFlash("success", "La réservation à bien été supprimée");

        return $this->redirectToRoute("admin_bookings_index");
    }
}
