<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ShopController extends AbstractController
{
    #[Route('/boutique', name: 'app_shop')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $products = $entityManager->getRepository(Product::class)->findAll();

        return $this->render('shop/index.html.twig', [
            'products' => $products
        ]);
    }

    #[Route('/boutique/recherche', name: 'app_shop_search', methods: ['POST'])]
    public function search(EntityManagerInterface $entityManager, Request $request): Response
    {
        $searchKey = $request->request->get('search');

        $products = $entityManager->getRepository(Product::class)->searchBy($searchKey);

        return $this->render('shop/research-result.html.twig', [
            'products' => $products
        ]);
    }
}
