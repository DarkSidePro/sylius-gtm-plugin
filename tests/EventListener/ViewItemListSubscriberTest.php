<?php

declare(strict_types=1);

namespace Tests\DarkSidePro\SyliusGtmPlugin\EventListener;

use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\Product;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use DarkSidePro\SyliusGtmPlugin\EventListener\ViewItemListSubscriber;
use DarkSidePro\SyliusGtmPlugin\Factory\EventFactory;

class ViewItemListSubscriberTest extends TestCase
{
    public function testOnViewItemListPushesEventToSession(): void
    {
        $session = $this->createMock(SessionInterface::class);
        $eventFactory = $this->createMock(EventFactory::class);
        $subscriber = new ViewItemListSubscriber($session, $eventFactory);

        $product = new Product();
        $product->setCode('P001');
        $product->setName('Test Product');
        $products = [$product];

        $event = $this->getMockBuilder('stdClass')
            ->addMethods(['getProducts'])
            ->getMock();
        $event->method('getProducts')->willReturn($products);

        $eventFactory->expects($this->once())
            ->method('createViewItemList')
            ->with($product, $this->arrayHasKey(0))
            ->willReturn(new \DarkSidePro\SyliusGtmPlugin\Model\Event('view_item_list', ['items' => []]));

        $session->expects($this->once())
            ->method('getFlashBag')
            ->willReturn(new class {
                public function add($key, $value) {
                    // Można dodać asercję lub logikę testową
                }
            });

        $subscriber->onViewItemList($event);
    }
}
