<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductDetailController extends AbstractController
{
    #[Route('/produit/details/{id}', name: 'app_product_detail')]
    public function index(EntityManagerInterface $entityManager, Product $product): Response
    {
        return $this->render('product_detail/index.html.twig', [
            'product' => $product,
        ]);
    }
}
