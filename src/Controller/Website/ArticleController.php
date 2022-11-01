<?php

namespace App\Controller\Website;

use App\Entity\Article;
use App\Repository\ArticleRepository;
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

    #[Route('/article/{id}', name: 'app_detail')]
    public function detail(Article $article): Response
    {   

        return $this->render('website/article/detail.html.twig', [
            'article' => $article
        ]);
    }

}
