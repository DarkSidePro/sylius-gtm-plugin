<?php

namespace DarkSidePro\SyliusGtmPlugin\Factory;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use DarkSidePro\SyliusGtmPlugin\Model\Event;

interface EventFactoryInterface
{
    public function createAddToCart(OrderItemInterface $orderItem): Event;

    public function createRemoveFromCart(OrderItemInterface $orderItem): Event;

    public function createViewItem(ProductVariantInterface $variant): Event;

    public function createViewItemList(ProductInterface $product, array $params = []): Event;

    public function createBeginCheckout(OrderInterface $order): Event;

    public function createPurchase(OrderInterface $order): Event;

    public function createAddPaymentInfo(PaymentInterface $payment): Event;
}