<?php

declare(strict_types=1);

namespace DarkSidePro\SyliusGtmPlugin;

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
}
