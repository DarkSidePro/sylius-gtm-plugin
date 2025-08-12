<?php

namespace DarkSidePro\SyliusGtmPlugin\EventListener;

use Sylius\Bundle\OrderBundle\Event\OrderItemEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final class AddToCartSubscriber implements EventSubscriberInterface
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.order_item.post_add' => 'onAddToCart',
        ];
    }

    public function onAddToCart(OrderItemEvent $event): void
    {
        $orderItem = $event->getOrderItem();
        $product = $orderItem->getProduct();
        
        $request = $this->requestStack->getCurrentRequest();
        if (null === $request) {
            return;
        }
        
        $session = $request->getSession();
        $gtmEvents = $session->get('gtm_events', []);
        $gtmEvents[] = [
            'event' => 'add_to_cart',
            'ecommerce' => [
                'items' => [
                    [
                        'id' => $product->getCode(),
                        'name' => $product->getName(),
                        'quantity' => $orderItem->getQuantity(),
                        'price' => $orderItem->getUnitPrice() / 100,
                    ]
                ]
            ]
        ];
        $session->set('gtm_events', $gtmEvents);
    }
}
