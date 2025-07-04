<?php

declare(strict_types=1);

namespace DarkSidePro\SyliusGtmPlugin\EventListener;

use Sylius\Bundle\CoreBundle\Event\OrderCompleteEvent;
use DarkSidePro\SyliusGtmPlugin\Model\Event;

final class PurchaseSubscriber extends AbstractGtmSubscriber
{
    public static function getSubscribedEvents(): array
    {
        return [
            OrderCompleteEvent::class => 'onPurchaseComplete',
        ];
    }

    public function onPurchaseComplete(OrderCompleteEvent $event): void
    {
        $this->addEvent($this->eventFactory->createPurchase($event->getOrder()));
    }
}