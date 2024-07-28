<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class CheckoutController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {

    }

    #[Route('/checkout', name: 'app_checkout')]
    public function index(SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);
        $totalPerItem = 0;
        $finalAmount = 0;

        if (!empty($cart)) {
            $product = null;
            foreach ($cart as $item => $quantity) {
                $product = $this->entityManager->getRepository(Product::class)->findOneBy(['id' => $item]);
                if (!$product) {
                    $cart = $session->get('cart', []);
                    unset($cart[$item]);
                    $session->set('cart', $cart);
                    continue;
                }

                $totalPerItem = $product->getPrice() * $quantity;
                $finalAmount += $totalPerItem;

                $checkoutFinaleCart[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'totalPerItem' => $totalPerItem,
                ];
            }
        }

        $session->set('cart', $cart);

        return $this->render('checkout/index.html.twig', [
            'checkoutCart' => $checkoutFinaleCart ?? null,
            'finalAmount' => $finalAmount
        ]);
    }
}
