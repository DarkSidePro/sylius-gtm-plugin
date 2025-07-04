<?php

namespace DarkSidePro\SyliusGtmPlugin\EventListener;

use Sylius\Component\Core\Model\OrderItemInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use DarkSidePro\SyliusGtmPlugin\Factory\EventFactory;

final class RemoveFromCartSubscriber implements EventSubscriberInterface
{
    private SessionInterface $session;
    private EventFactory $eventFactory;

    public function __construct(SessionInterface $session, EventFactory $eventFactory)
    {
        $this->session = $session;
        $this->eventFactory = $eventFactory;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.order_item.post_remove' => 'onRemoveFromCart',
        ];
    }

    public function onRemoveFromCart($event): void
    {
        if (method_exists($event, 'getOrderItem')) {
            /** @var OrderItemInterface $orderItem */
            $orderItem = $event->getOrderItem();
            $removeEvent = $this->eventFactory->createRemoveFromCart($orderItem);
            $this->session->getFlashBag()->add('gtm_event', $removeEvent->toArray());
        }
    }
}
