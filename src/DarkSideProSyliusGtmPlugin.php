<?php

declare(strict_types=1);

namespace DarkSidePro\SyliusGtmPlugin;

use DarkSidePro\SyliusGtmPlugin\DependencyInjection\GtmExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class DarkSideProSyliusGtmPlugin extends Bundle
{
    /**
     * Returns the bundle's path.
     */
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    /**
     * Returns the bundle container extension.
     */
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new GtmExtension();
    }
}
