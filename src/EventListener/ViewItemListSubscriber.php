<?php

namespace DarkSidePro\SyliusGtmPlugin\EventListener;

use Sylius\Component\Core\Model\ProductInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use DarkSidePro\SyliusGtmPlugin\Factory\EventFactory;

final class ViewItemListSubscriber implements EventSubscriberInterface
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
            'sylius.shop.product.index' => 'onViewItemList',
        ];
    }

    public function onViewItemList($event): void
    {
        if (method_exists($event, 'getProducts')) {
            /** @var ProductInterface[] $products */
            $products = $event->getProducts();
            $items = [];
            foreach ($products as $product) {
                $items[] = [
                    'item_id' => $product->getCode(),
                    'item_name' => $product->getName(),
                ];
            }
            $viewListEvent = $this->eventFactory->createViewItemList($products[0] ?? null, $items);
            $this->session->getFlashBag()->add('gtm_event', $viewListEvent->toArray());
        }
    }
}
