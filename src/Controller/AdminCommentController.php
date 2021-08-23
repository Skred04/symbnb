<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\AdminCommentType;
use App\Repository\CommentRepository;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCommentController extends AbstractController
{
    /**
     * Permet d'afficher l'interface d'administration des commentaires
     * @Route("/admin/comments/{page<\d+>?1}", name="admin_comments_index")
     */
    public function index(CommentRepository $comments, $page, PaginationService $pagination): Response
    {
        $pagination->setEntityClass(Comment::class)->setPage($page);
        return $this->render('admin/comment/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }


    /**
     * Permet d'afficher le formulaire d'edition d'un commentaire
     *
     * @Route ("/admin/comments/{id}/edit", name="admin_comments_edit")
     * @param Comment $comment
     * @return Response
     */
    public function edit(Comment $comment, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(AdminCommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $manager->persist($comment);
            $manager->flush();

            $this->addFlash("success", "Le commentaire n°{$comment->getId()} à bien été modifiée");
            return $this->redirectToRoute("admin_comment_index");
        }

        return $this->render("admin/comment/edit.html.twig", [
            "form" => $form->createView(),
            "comment" => $comment
        ]);
    }

    /**
     * Permet de supprimer un commentaire
     *
     * @Route ("/admin/comments/{id}", name="admin_comments_delete")
     *
     * @param Comment $comment
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete (Comment $comment, EntityManagerInterface $manager): Response {
        $commentId = $comment->getId();
        $manager->remove($comment);
        $manager->flush();

        $this->addFlash("success", "L'annonce n°$commentId à bien été supprimée");

        return $this->redirectToRoute("admin_comment_index");
    }
}
