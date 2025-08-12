<?php

namespace DarkSidePro\SyliusGtmPlugin\EventListener;

use Sylius\Component\Core\Model\OrderItemInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use DarkSidePro\SyliusGtmPlugin\Factory\EventFactory;

final class RemoveFromCartSubscriber implements EventSubscriberInterface
{
    private RequestStack $requestStack;
    private EventFactory $eventFactory;

    public function __construct(RequestStack $requestStack, EventFactory $eventFactory)
    {
        $this->requestStack = $requestStack;
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
            
            $request = $this->requestStack->getCurrentRequest();
            if (null !== $request) {
                $session = $request->getSession();
                $gtmEvents = $session->get('gtm_events', []);
                $gtmEvents[] = $removeEvent->toArray();
                $session->set('gtm_events', $gtmEvents);
            }
        }
    }
}
