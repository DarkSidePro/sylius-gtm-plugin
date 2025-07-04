<?php

declare(strict_types=1);

namespace DarkSidePro\SyliusGtmPlugin\Factory;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use DarkSidePro\SyliusGtmPlugin\Model\Event;

final class EventFactory
{
    private string $defaultCurrency;

    public function __construct(string $defaultCurrency)
    {
        $this->defaultCurrency = $defaultCurrency;
    }

    public function createPurchase(OrderInterface $order): Event
    {
        return new Event('purchase', [
            'transaction_id' => $order->getNumber(),
            'value' => $order->getTotal() / 100,
            'tax' => $order->getTaxTotal() / 100,
            'shipping' => $order->getShippingTotal() / 100,
            'currency' => $order->getCurrencyCode() ?? $this->defaultCurrency,
            'items' => array_map([$this, 'createItemFromOrderItem'], $order->getItems()->toArray())
        ]);
    }

    public function createAddToCart(OrderItemInterface $orderItem): Event
    {
        return new Event('add_to_cart', [
            'items' => [$this->createItemFromOrderItem($orderItem)]
        ]);
    }

    public function createRemoveFromCart(OrderItemInterface $orderItem): Event
    {
        return new Event('remove_from_cart', [
            'items' => [$this->createItemFromOrderItem($orderItem)]
        ]);
    }

    public function createBeginCheckout(OrderInterface $order): Event
    {
        return new Event('begin_checkout', [
            'value' => $order->getTotal() / 100,
            'currency' => $order->getCurrencyCode() ?? $this->defaultCurrency,
            'items' => array_map([$this, 'createItemFromOrderItem'], $order->getItems()->toArray())
        ]);
    }

    public function createViewItem(ProductVariantInterface $variant): Event
    {
        $product = $variant->getProduct();

        return new Event('view_item', [
            'items' => [[
                'item_id' => $variant->getCode(),
                'item_name' => $product->getName(),
                'price' => $variant->getPrice() / 100,
                'currency' => $this->defaultCurrency,
            ]]
        ]);
    }

    public function createViewItemList(ProductInterface $product, array $items): Event
    {
        return new Event('view_item_list', [
            'item_list_id' => $product->getId(),
            'item_list_name' => $product->getName(),
            'items' => $items
        ]);
    }

    private function createItemFromOrderItem(OrderItemInterface $orderItem): array
    {
        $variant = $orderItem->getVariant();
        $product = $variant->getProduct();

        return [
            'item_id' => $variant->getCode(),
            'item_name' => $product->getName(),
            'price' => $orderItem->getUnitPrice() / 100,
            'quantity' => $orderItem->getQuantity(),
        ];
    }
}