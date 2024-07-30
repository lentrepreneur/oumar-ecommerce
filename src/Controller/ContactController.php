<?php

namespace App\Controller;

use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(): Response
    {
        return $this->render('contact/index.html.twig');
    }

    #[Route('/contact/submit', name: 'app_contact_submit')]
    public function submitMessage(Request $request, EntityManagerInterface $entityManager)
    {
        $data = $request->request->all();

        if (empty($data['fullName']) or empty($data['email']) or empty($data['message'])) {
            $this->addFlash('error', 'Erreur : renseigner les champs obligatoires');
            return $this->redirectToRoute('app_contact');
        }

        $contact = new Contact();
        $contact->setFullName($data['fullName']);
        $contact->setPhone($data['phone']);
        $contact->setEmail($data['email']);
        $contact->setMessage($data['message']);

        $entityManager->persist($contact);
        $entityManager->flush();

        $this->addFlash('success', 'Votre message a ete envoye avec succes, merci de nous avoir contacte.');
        return $this->redirectToRoute('app_contact');
    }
}
