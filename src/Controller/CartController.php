<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {

    }

    #[Route('/cart', name: 'app_cart')]
    public function index(SessionInterface $session): Response
    {
        $finaleCart = [];
        $cart = $session->get('cart', []);

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

                $finaleCart[] = [
                    'product' => $product,
                    'quantity' => $quantity
                ];
            }
        }

        return $this->render('cart/index.html.twig', [
            'cart' => $finaleCart,
        ]);
    }

    #[Route('/cart/add/{item}', name: 'app_cart_add', methods: ['POST'])]
    public function addItemCart($item, Request $request, SessionInterface $session)
    {
        $data = json_decode($request->getContent(), true);
        $quantity = $data['quantity'];

        $cart = $session->get('cart', []);

        if (isset($cart[$item])) {
            $cart[$item] = $quantity;
        } else {
            $cart[$item] = $quantity;
        }

        $session->set('cart', $cart);

        return new JsonResponse(['success' => true, 'cart' => $cart]);
    }

    #[Route('/cart/decrease/{item}', name: 'app_cart_decrease', methods: ['POST'])]
    public function decreaseItemCart($item, SessionInterface $session)
    {
        $cart = $session->get('cart', []);

        if ($cart[$item] === 1) {
            unset($cart[$item]);
        } else {
            $cart[$item]--;
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/increase/{item}', name: 'app_cart_increase', methods: ['POST'])]
    public function increaseItemCart($item, SessionInterface $session)
    {
        $cart = $session->get('cart', []);

        $cart[$item]++;

        $session->set('cart', $cart);

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/remove/{item}', name: 'app_cart_remove', methods: ['POST'])]
    public function deleteItemCart($item, SessionInterface $session)
    {
        $cart = $session->get('cart', []);
        unset($cart[$item]);
        $session->set('cart', $cart);

        return $this->redirectToRoute('app_cart');
    }
}
