<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\SiteInformation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class MailService
{
    public function __construct(private MailerInterface $mailer, private Environment $template, private EntityManagerInterface $manager)
    {

    }

    public function sendInvoiceNotification(Order $order, )
    {
        $siteInfo = $this->manager->getRepository(SiteInformation::class)->findOneBy(['id' => 1]);

        $emailContent = $this->template->render('mail/invoice-mail.html.twig', [
            'order' => $order,
            'siteInfo' => $siteInfo
        ]);

        $email = (new Email())
            ->from($siteInfo->getEmail())
            ->to($order->getCustomer()->getEmail())
            ->subject('Facture N - ' . $order->getId())
            ->html($emailContent);

        $this->mailer->send($email);
    }

    public function sendOrderNotification(Order $order, )
    {
        $siteInfo = $this->manager->getRepository(SiteInformation::class)->findOneBy(['id' => 1]);

        $emailContent = $this->template->render('mail/order-mail.html.twig', [
            'order' => $order,
            'siteInfo' => $siteInfo
        ]);

        $email = (new Email())
            ->from($siteInfo->getEmail())
            ->to($siteInfo->getEmail())
            ->subject('Commande N - ' . $order->getId())
            ->html($emailContent);

        $this->mailer->send($email);
    }
}