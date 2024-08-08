<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Entity\Product;
use App\Entity\User;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class CheckoutController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, private MailService $mailService, private ?User $user, private Security $security)
    {
        $this->user = $security->getUser();
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

    #[Route('/checkout/order', name: 'app_checkout_order', methods: ['POST'])]
    public function procedOrder(Request $request, SessionInterface $session)
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        $this->entityManager->beginTransaction(); // Démarrer la transaction

        try {
            $data = $request->request->all();

            $cart = $session->get('cart', []);
            $totalPerItem = 0;
            $finalAmount = 0;
            $totalQuantity = 0;

            $user = $this->user;

            $user->setPhone($data['phone'] ?? null);
            $user->setAddress($data['address'] ?? null);
            $user->setCity($data['city'] ?? null);
            $this->entityManager->persist($user);

            $order = new Order();

            $order->setCustomer($user);

            if (!empty($cart)) {
                foreach ($cart as $item => $quantity) {
                    $product = $this->entityManager->getRepository(Product::class)->findOneBy(['id' => $item]);
                    if (!$product) {
                        unset($cart[$item]);
                        $session->set('cart', $cart);
                        continue;
                    }

                    $totalPerItem = $product->getPrice() * $quantity;
                    $totalQuantity += $quantity;
                    $finalAmount += $totalPerItem;

                    $orderDetail = new OrderDetail();

                    $orderDetail->setProducts($product);
                    $orderDetail->setQuantity($quantity);
                    $orderDetail->setAmount($totalPerItem);
                    $orderDetail->setCustomerOrder($order);

                    $this->entityManager->persist($orderDetail);
                }
            }

            $order->setAmount($finalAmount);
            $order->setQuantity($totalQuantity);
            $order->setStatus(Order::STATUS_PREPARATION);

            $this->entityManager->persist($order);

            $this->entityManager->flush();

            // Envoi du mail
            $this->mailService->sendInvoiceNotification($order);

            $this->entityManager->commit();

            $session->remove('cart');

            $this->addFlash('success', 'Commande effectuée avec succès');

            return $this->redirectToRoute(
                'app_profile_order_show',
                ['id' => $order->getId()]
            );

        } catch (\Exception $e) {
            $this->entityManager->rollback();
            $this->addFlash('error', 'Une erreur est survenue lors du traitement de votre commande.');

            return $this->redirectToRoute('app_checkout');
        }
    }
}
