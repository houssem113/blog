<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3Validator;

#[Route('/admin/article')]
class ArticleController extends AbstractController
{

    #[Route('/new', name: 'app_article_new')]
    public function new(Request $request, ArticleRepository $articleRepository, Security $security, Recaptcha3Validator $recaptcha3Validator): Response
    {
        $article = new Article();
        $article->setAuthor($security->getUser());
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $score = $recaptcha3Validator->getLastResponse()->getScore();
            if($score>0.5){
                $articleRepository->save($article, true);
                return $this->redirectToRoute('app_article', [], Response::HTTP_SEE_OTHER);
            } 
        }

        return $this->renderForm('admin/article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }


}