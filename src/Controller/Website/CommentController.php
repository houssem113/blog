<?php

namespace App\Controller\Website;

use App\Entity\Article;
use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


#[Route('/article/comment')]
class CommentController extends AbstractController
{   

    public function __construct(Private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/{article}/{id}', name: 'article_comment_delete')]
    public function deleteCommentPost(Request $request, Article $article, Comment $comment): Response
    {   
        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($comment);
            $this->entityManager->flush();
        }
        return  $this->redirectToRoute('app_detail', ['id' => $article->getId()]);
    }
}
