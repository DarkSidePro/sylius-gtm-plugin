<?php

namespace DarkSidePro\SyliusGtmPlugin\EventListener;

use Sylius\Bundle\OrderBundle\Event\OrderItemEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class AddToCartSubscriber implements EventSubscriberInterface
{
    private SessionInterface $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
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
        $this->session->getFlashBag()->add('gtm_event', [
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
        ]);
    }
}
