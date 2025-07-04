<?php

declare(strict_types=1);

namespace DarkSidePro\SyliusGtmPlugin\EventListener;

use Sylius\Bundle\CoreBundle\Event\OrderPlacedEvent;
use DarkSidePro\SyliusGtmPlugin\Model\Event;

final class CheckoutSubscriber extends AbstractGtmSubscriber
{
    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.order.pre_complete' => 'onBeginCheckout',
        ];
    }

    public function onBeginCheckout(OrderPlacedEvent $event): void
    {
        $this->addEvent($this->eventFactory->createBeginCheckout($event->getOrder()));
    }
}