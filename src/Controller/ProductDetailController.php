<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductDetailController extends AbstractController
{
    #[Route('/produit/details/{id}', name: 'app_product_detail')]
    public function index(?Product $product): Response
    {
        if (!$product) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('product_detail/index.html.twig', [
            'product' => $product,
        ]);
    }
}
