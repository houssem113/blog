<?php

namespace App\Controller\Website;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{   
   
    #[Route('/', name: 'app_article')]
    public function index( ): Response
    {   
        return $this->render('website/article/index.html.twig', [
            'articles' => 'test',
        ]);
    }

}
