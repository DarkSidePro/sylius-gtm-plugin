<?php

declare(strict_types=1);

namespace DarkSidePro\SyliusGtmPlugin\EventListener;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Sylius\Bundle\UiBundle\Event\MenuBuilderEvent;

final class GtmHeadSubscriber implements EventSubscriberInterface
{
    private string $gtmId;
    private Environment $twig;

    public function __construct(string $gtmId, Environment $twig)
    {
        $this->gtmId = $gtmId;
        $this->twig = $twig;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.layout.head' => ['renderGtmHead', 0],
        ];
    }

    public function renderGtmHead(): string
    {
        try {
            return $this->twig->render('@DarkSideProSyliusGtmPlugin/Gtm/head.html.twig', [
                'gtm_id' => $this->gtmId,
            ]);
        } catch (LoaderError | RuntimeError | SyntaxError $e) {
            return '<!-- GTM Head Error: ' . $e->getMessage() . ' -->';
        }
    }
}