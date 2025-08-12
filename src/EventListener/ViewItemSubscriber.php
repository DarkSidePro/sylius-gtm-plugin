<?php

namespace DarkSidePro\SyliusGtmPlugin\EventListener;

use Sylius\Component\Core\Model\ProductVariantInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use DarkSidePro\SyliusGtmPlugin\Factory\EventFactory;

final class ViewItemSubscriber implements EventSubscriberInterface
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
            'sylius.shop.product.show' => 'onViewItem',
        ];
    }

    public function onViewItem($event): void
    {
        if (method_exists($event, 'getProductVariant')) {
            /** @var ProductVariantInterface $variant */
            $variant = $event->getProductVariant();
            $viewEvent = $this->eventFactory->createViewItem($variant);
            
            $request = $this->requestStack->getCurrentRequest();
            if (null !== $request) {
                $session = $request->getSession();
                $gtmEvents = $session->get('gtm_events', []);
                $gtmEvents[] = $viewEvent->toArray();
                $session->set('gtm_events', $gtmEvents);
            }
        }
    }
}
