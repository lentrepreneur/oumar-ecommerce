<?php

namespace App\Controller;

use App\Entity\HeaderPromo;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $headerPromo = $entityManager->getRepository(HeaderPromo::class)->findAll();
        $products = $entityManager->getRepository(Product::class)->findAll();

        return $this->render('home/index.html.twig', [
            'header_promos' => $headerPromo,
            'products' => $products
        ]);
    }
}
