<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class ProfileController extends AbstractController
{
    public function __construct(private EntityManagerInterface $manager, private ?User $user, private Security $security)
    {
        $this->user = $security->getUser();
    }

    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        if (!$this->user) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('profile/info.html.twig');
    }

    #[Route('/profile/commandes', name: 'app_profile_orders')]
    public function orders(): Response
    {
        if (!$this->user) {
            return $this->redirectToRoute('app_login');
        }

        $orders = $this->user->getOrders();

        return $this->render('profile/orders.html.twig', [
            'orders' => $orders,
        ]);
    }

    #[Route('/profile/commandes/{id}', name: 'app_profile_order_show')]
    public function orderDetails(?Order $order): Response
    {
        if (!$order) {
            $this->addFlash('error', 'Cette commande n\'existe pas');
            return $this->redirectToRoute('app_orders');
        }

        if (!$this->user) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('profile/order-detail.html.twig', [
            'order' => $order,
        ]);
    }

    #[Route('/profile/modifier', name: 'app_profile_update')]
    public function update(): Response
    {
        if (!$this->user) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('profile/update-info.html.twig');
    }


    #[Route('/profile/modifier/validation', name: 'app_profile_update_validate')]
    public function updateValidate(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        if (!$this->user) {
            return $this->redirectToRoute('app_login');
        }

        $user = $this->user;

        $data = $request->request->all();

        if (
            empty($data['firstName']) or
            empty($data['lastName']) or
            empty($data['email']) or
            empty($data['phone'])
        ) {
            $this->addFlash('error', 'Renseignez les champs obligatoires');
            return $this->redirectToRoute('app_profile_update');
        }

        $user->setFirstName($data['firstName']);
        $user->setLastName($data['lastName']);
        $user->setEmail($data['email']);
        $user->setPhone($data['phone']);
        $user->setAddress($data['address']);
        $user->setCity($data['city']);

        $oldPassword = $data['old_password'];
        $newPassword = $data['new_password'];

        if ($oldPassword && $newPassword) {
            if ($passwordHasher->isPasswordValid($this->user, $oldPassword)) {
                $encodedPassword = $passwordHasher->hashPassword($this->user, $newPassword);

                $user->setPassword($encodedPassword);
            } else {
                $this->addFlash('error', 'Ancien mot de passe incorrect');
                return $this->redirectToRoute('app_profile_update');
            }
        }

        $this->manager->persist($user);
        $this->manager->flush();

        $this->addFlash('success', 'Informations mis a jour avec succes');
        return $this->redirectToRoute('app_profile');
    }
}
