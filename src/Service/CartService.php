<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    private SessionInterface $session;
    public function __construct(private RequestStack $requestStack, private EntityManagerInterface $entityManager)
    {
        $this->session = $requestStack->getSession();
    }

    public function getCart()
    {
        return $this->session->get('cart');
    }

    public function getCount()
    {
        $cartCount = 0;

        $cart = $this->session->get('cart', []);

        $this->session->set('cart', $cart);

        foreach ($cart as $item => $quantity) {
            $cartCount += $quantity;
        }

        return $cartCount;
    }
}