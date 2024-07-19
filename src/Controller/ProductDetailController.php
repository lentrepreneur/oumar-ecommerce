<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductDetailController extends AbstractController
{
    #[Route('/produit/details', name: 'app_product_detail')]
    public function index(): Response
    {
        return $this->render('product_detail/index.html.twig', [
            'controller_name' => 'ProductDetailController',
        ]);
    }
}
