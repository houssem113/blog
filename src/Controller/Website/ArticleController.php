<?php

namespace App\Controller\Website;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\User;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3Validator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class ArticleController extends AbstractController
{   
    public function __construct(Private PaginatorInterface $paginator)
    {  
    }

    #[Route('/', name: 'app_article')]
    public function index(ArticleRepository $articleRepository, Request $request): Response
    {   
        $pagination = $this->paginator->paginate(
            $articleRepository->findBy([],['createdAt'=>'DESC']),
            $request->query->getInt('page', 1),
            3 
        );
        return $this->render('website/article/index.html.twig', [
            'articles' => $pagination,
        ]);
    }

    #[Route('/article/author/{id}', name: 'app_article_author')]
    public function postesByAuthor(User $author, Request $request): Response
    {   
        $posts = $this->paginator->paginate(
            $author->getArticles(),
            $request->query->getInt('page', 1),
            3
        );
        return $this->render('website/article/index.html.twig', [
            'articles' => $posts
        ]);
    }
    
    #[Route('/article/{id}', name: 'app_detail')]
    public function detail(Article $article, Request $request, EntityManagerInterface $entityManager, Recaptcha3Validator $recaptcha3Validator): Response
    {   
        $comment = new Comment();
        $comment->setArticle($article);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $score = $recaptcha3Validator->getLastResponse()->getScore();
            if($score>0.5){
                $entityManager->persist($comment);
                $entityManager->flush();
                return $this->redirectToRoute('app_detail', ['id' => $article->getId()]);
            }
        }
        return $this->render('website/article/detail.html.twig', [
            'article' => $article,
            'form' => $form->createView()
        ]);
    }

}
