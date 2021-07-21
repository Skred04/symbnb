<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCommentController extends AbstractController
{
    /**
     * Permet d'afficher l'interface d'administration des commentaires
     * @Route("/admin/comments", name="admin_comment_index")
     */
    public function index(CommentRepository $comments): Response
    {
        return $this->render('admin/comment/index.html.twig', [
            'comments' => $comments->findAll(),
        ]);
    }
}
