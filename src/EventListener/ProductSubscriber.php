<?php

declare(strict_types=1);

namespace DarkSidePro\SyliusGtmPlugin\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use DarkSidePro\SyliusGtmPlugin\Model\Event;

final class ProductSubscriber extends AbstractGtmSubscriber
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onProductPage',
        ];
    }

    public function onProductPage(ControllerEvent $event): void
    {
        $request = $event->getRequest();
        
        // View Item
        if ('sylius_shop_product_show' === $request->attributes->get('_route')) {
            $product = $request->attributes->get('product');
            if ($product instanceof ProductInterface) {
                $variant = $product->getVariants()->first();
                if ($variant instanceof ProductVariantInterface) {
                    $this->addEvent($this->eventFactory->createViewItem($variant));
                }
            }
        }
        
        // View Item List
        if ('sylius_shop_product_index' === $request->attributes->get('_route')) {
            $products = $request->attributes->get('products', []);
            if (is_array($products)) {
                foreach ($products as $product) {
                    if ($product instanceof ProductInterface) {
                        $variant = $product->getVariants()->first();
                        if ($variant instanceof ProductVariantInterface) {
                            $this->addEvent($this->eventFactory->createViewItemList(
                                $product,
                                [$this->createItemFromProduct($product, $variant)]
                            ));
                        }
                    }
                }
            }
        }
    }

    private function createItemFromProduct(ProductInterface $product, ProductVariantInterface $variant): array
    {
        return [
            'item_id' => $variant->getCode(),
            'item_name' => $product->getName(),
            'price' => $variant->getPrice() / 100,
        ];
    }
}