<?php

namespace App\Controller;

use App\Entity\Order;
use Dompdf\Dompdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class InvoiceController extends AbstractController
{
    #[Route('/invoice/client/order/{id}', name: 'app_invoice_customer')]
    public function index(?Order $order): Response
    {
        if (!$order) {
            $this->addFlash('error', 'Cette commande n\'existe pas!');
            return $this->redirectToRoute('app_profile_orders');
        }

        if ($order->getCustomer() != $this->getUser()) {
            $this->addFlash('error', 'Erreur : Cette commande n\'existe pas!');
            return $this->redirectToRoute('app_profile_orders');
        }

        $dompdf = new Dompdf();
        $html = $this->renderView('invoice/index.html.twig', [
            'order' => $order
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $dompdf->stream('facture-alamin-design-' . $order->getId() . '.pdf', [
            'Attachment' => false
        ]);

        exit();
    }


    #[Route('/invoice/admin/order/{id}', name: 'app_invoice_admin')]
    public function admin(?Order $order): Response
    {
        if (!$order) {
            $this->addFlash('error', 'Cette commande n\'existe pas!');
            return $this->redirectToRoute('admin');
        }

        $dompdf = new Dompdf();

        $html = $this->renderView('invoice/index.html.twig', [
            'order' => $order
        ]);

        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $dompdf->stream('facture-alamin-design-' . $order->getId() . '.pdf', [
            'Attachment' => false
        ]);

        exit();
    }
}
