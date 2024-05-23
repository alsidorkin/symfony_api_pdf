<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MyController extends AbstractController
{
    #[Route('/my', name: 'app_my')]
    public function index(): Response
    {
        return $this->render('my/index.html.twig', [
            'controller_name' => 'MyController',
        ]);
    }

    #[Route('/my/another', name: 'app_my_another')]
    public function anotherAction(): Response
    {
        return new Response('<html><body>Another action!</body></html>');
    }
}
