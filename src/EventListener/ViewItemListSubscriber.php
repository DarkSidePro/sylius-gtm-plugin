<?php

namespace DarkSidePro\SyliusGtmPlugin\EventListener;

use DarkSidePro\SyliusGtmPlugin\Factory\EventFactoryInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

final class ViewItemListSubscriber implements EventSubscriberInterface
{
    private FlashBagInterface $flashBag;
    private EventFactoryInterface $eventFactory;

    public function __construct(FlashBagInterface $flashBag, EventFactoryInterface $eventFactory)
    {
        $this->flashBag = $flashBag;
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
            $this->flashBag->add('gtm_event', $viewListEvent->toArray());
        }
    }
}
