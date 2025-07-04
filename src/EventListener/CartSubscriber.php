<?php

declare(strict_types=1);

namespace DarkSidePro\SyliusGtmPlugin\EventListener;

use Sylius\Bundle\CoreBundle\Event\OrderItemAddedEvent;
use Sylius\Bundle\CoreBundle\Event\OrderItemRemovedEvent;
use DarkSidePro\SyliusGtmPlugin\Model\Event;

final class CartSubscriber extends AbstractGtmSubscriber
{
    public static function getSubscribedEvents(): array
    {
        return [
            OrderItemAddedEvent::class => 'onAddToCart',
            OrderItemRemovedEvent::class => 'onRemoveFromCart',
        ];
    }

    public function onAddToCart(OrderItemAddedEvent $event): void
    {
        $this->addEvent($this->eventFactory->createAddToCart($event->getOrderItem()));
    }

    public function onRemoveFromCart(OrderItemRemovedEvent $event): void
    {
        $this->addEvent($this->eventFactory->createRemoveFromCart($event->getOrderItem()));
    }
}