<?php

namespace DarkSidePro\SyliusGtmPlugin\EventListener;

use Sylius\Component\Core\Model\ProductVariantInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use DarkSidePro\SyliusGtmPlugin\Factory\EventFactory;

final class ViewItemSubscriber implements EventSubscriberInterface
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
            'sylius.shop.product.show' => 'onViewItem',
        ];
    }

    public function onViewItem($event): void
    {
        if (method_exists($event, 'getProductVariant')) {
            /** @var ProductVariantInterface $variant */
            $variant = $event->getProductVariant();
            $viewEvent = $this->eventFactory->createViewItem($variant);
            $this->session->getFlashBag()->add('gtm_event', $viewEvent->toArray());
        }
    }
}
