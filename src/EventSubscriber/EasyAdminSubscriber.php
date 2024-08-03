<?php

namespace App\EventSubscriber;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use App\Security\AccountNotVerifiedAuthenticationException;
use App\Service\MailService;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityDeletedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    public function __construct(private MailService $mailService)
    {

    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['setDiscountAmountOnPersist'],
            BeforeEntityUpdatedEvent::class => ['setDiscountAmountOnUpdate'],
            BeforeEntityDeletedEvent::class => ['checkOrder'],
            BeforeEntityUpdatedEvent::class => ['updateOrderStatus'],
            CheckPassportEvent::class => ['onCheckPassport', -10],
        ];
    }

    public function setDiscountAmountOnPersist(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Product)) {
            return;
        }

        $discountAmount = $entity->getPrice() - ($entity->getPrice() * $entity->getDiscount());
        $entity->setDiscountAmount($discountAmount);
    }

    public function setDiscountAmountOnUpdate(BeforeEntityUpdatedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Product)) {
            return;
        }

        $discountAmount = $entity->getPrice() - ($entity->getPrice() * $entity->getDiscount());
        $entity->setDiscountAmount($discountAmount);
    }

    public function checkOrder(BeforeEntityDeletedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Product)) {
            return;
        }

        if ($entity->getOrderDetails()) {
            return;
        }
    }

    public function updateOrderStatus(BeforeEntityUpdatedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Order)) {
            return;
        }

        if ($entity->getStatus() == Order::STATUS_PAID) {
            $entity->setStatus(Order::STATUS_PAID);
            $this->mailService->sendInvoiceNotification($entity);
        }
    }

    public function onCheckPassport(CheckPassportEvent $event)
    {
        $passport = $event->getPassport();
        $user = $passport->getUser();

        if (!$user instanceof User) {
            throw new \Exception('Unexpected user type');
        }

        if (!$user->isVerified()) {
            throw new AccountNotVerifiedAuthenticationException();
        }
    }
}