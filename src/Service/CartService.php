<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    public function __construct(private RequestStack $requestStack, private EntityManagerInterface $entityManager)
    {
    }

    public function getCart()
    {
        return $this->requestStack->getSession()->get('cart');
    }

    public function getCount()
    {
        $cartCount = 0;

        $cart = $this->requestStack->getSession()->get('cart', []);

        $this->requestStack->getSession()->set('cart', $cart);

        foreach ($cart as $item => $quantity) {
            $cartCount += $quantity;
        }

        return $cartCount;
    }
}