<?php

namespace App\Controller\Admin;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

#[Route('/admin/imprint')]
class ImprintController extends AbstractController
{

    #[Route('/', name: 'app_imprint')]
    public function index(): Response
    {
        return $this->renderForm('admin/imprint/index.html.twig');
    }


}