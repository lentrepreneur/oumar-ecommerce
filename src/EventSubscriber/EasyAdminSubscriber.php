<?php

namespace App\EventSubscriber;

use App\Entity\Order;
use App\Entity\Product;
use App\Service\MailService;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityDeletedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

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
            BeforeEntityUpdatedEvent::class => ['updateOrderStatus']
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

        // if ($entity->getStatus() != Order::STATUS_PAID) {
        //     if ($entity->getStatus() == Order::STATUS_CANCELED) {
        //         $entity->setStatus(Order::STATUS_CANCELED);
        //         return;
        //     }

        //     if ($entity->getStatus() == Order::STATUS_PENDING) {
        //         $entity->setStatus(Order::STATUS_PENDING);
        //         return;
        //     }

        //     if ($entity->getStatus() == Order::STATUS_PREPARATION) {
        //         $entity->setStatus(Order::STATUS_PREPARATION);
        //         return;
        //     }
        // }
    }
}