<?php

declare(strict_types=1);

namespace DarkSidePro\SyliusGtmPlugin\EventListener;

use Sylius\Bundle\CoreBundle\Event\OrderCompleteEvent;
use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use DarkSidePro\SyliusGtmPlugin\Factory\EventFactory;
use DarkSidePro\SyliusGtmPlugin\Model\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

abstract class AbstractGtmSubscriber implements EventSubscriberInterface
{
    protected const SESSION_KEY = 'gtm_events';
    
    protected EventFactory $eventFactory;
    protected RequestStack $requestStack;

    public function __construct(EventFactory $eventFactory, RequestStack $requestStack)
    {
        $this->eventFactory = $eventFactory;
        $this->requestStack = $requestStack;
    }

    protected function addEvent(Event $event): void
    {
        $session = $this->getSession();
        $events = $session->get(self::SESSION_KEY, []);
        $events[] = $event->toArray();
        $session->set(self::SESSION_KEY, $events);
    }

    protected function getSession(): SessionInterface
    {
        return $this->requestStack->getSession();
    }
}