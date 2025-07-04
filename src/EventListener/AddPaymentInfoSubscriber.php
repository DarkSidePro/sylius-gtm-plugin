<?php

namespace DarkSidePro\SyliusGtmPlugin\EventListener;

use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use DarkSidePro\SyliusGtmPlugin\Factory\EventFactory;

final class AddPaymentInfoSubscriber implements EventSubscriberInterface
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
            'sylius.shop.checkout.complete' => 'onAddPaymentInfo',
        ];
    }

    public function onAddPaymentInfo($event): void
    {
        if (method_exists($event, 'getOrder')) {
            /** @var OrderInterface $order */
            $order = $event->getOrder();
            $paymentInfo = [
                'payment_type' => $order->getLastPayment() ? $order->getLastPayment()->getMethod()->getName() : null,
                'value' => $order->getTotal() / 100,
                'currency' => $order->getCurrencyCode(),
            ];
            $this->session->getFlashBag()->add('gtm_event', [
                'event' => 'add_payment_info',
                'ecommerce' => $paymentInfo
            ]);
        }
    }
}
