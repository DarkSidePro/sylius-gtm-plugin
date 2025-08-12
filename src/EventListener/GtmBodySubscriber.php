<?php

declare(strict_types=1);

namespace DarkSidePro\SyliusGtmPlugin\EventListener;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use DarkSidePro\SyliusGtmPlugin\Service\GtmConfigProvider;

final class GtmBodySubscriber implements EventSubscriberInterface
{
    private GtmConfigProvider $gtmConfigProvider;
    private Environment $twig;

    public function __construct(GtmConfigProvider $gtmConfigProvider, Environment $twig)
    {
        $this->gtmConfigProvider = $gtmConfigProvider;
        $this->twig = $twig;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.layout.body' => ['renderGtmBody', 0],
        ];
    }

    public function renderGtmBody(): string
    {
        try {
            return $this->twig->render('@SyliusGtm/Gtm/body.html.twig', [
                'gtm_id' => $this->gtmConfigProvider->getContainerId(),
            ]);
        } catch (LoaderError | RuntimeError | SyntaxError $e) {
            return '<!-- GTM Body Error: ' . $e->getMessage() . ' -->';
        }
    }
}