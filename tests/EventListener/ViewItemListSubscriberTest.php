<?php

declare(strict_types=1);

namespace Tests\DarkSidePro\SyliusGtmPlugin\EventListener;

use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\Product;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use DarkSidePro\SyliusGtmPlugin\EventListener\ViewItemListSubscriber;
use DarkSidePro\SyliusGtmPlugin\Factory\EventFactoryInterface;
use DarkSidePro\SyliusGtmPlugin\Model\Event;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class ViewItemListSubscriberTest extends TestCase
{
    public function testOnViewItemListPushesEventToSession(): void
    {
        /** @var FlashBagInterface&\PHPUnit\Framework\MockObject\MockObject $flashBag */
        $flashBag = $this->createMock(FlashBagInterface::class);

        /** @var EventFactoryInterface&\PHPUnit\Framework\MockObject\MockObject $eventFactory */
        $eventFactory = $this->createMock(EventFactoryInterface::class);
        $subscriber = new ViewItemListSubscriber($flashBag, $eventFactory);

        $product = new Product();
        $product->setCurrentLocale('pl_PL');
        $product->setFallbackLocale('pl_PL');
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
            ->willReturn(new Event('view_item_list', ['items' => []]));

        $flashBag->expects($this->once())
            ->method('add')
            ->with(
                'gtm_event',
                [
                    'event' => 'view_item_list',
                    'ecommerce' => [], // lub podaj przykładową strukturę, jeśli jest wymagana
                ]
            );

        $subscriber->onViewItemList($event);

        $this->addToAssertionCount(1); // Poprawna asercja dla PHPUnit
    }
}
