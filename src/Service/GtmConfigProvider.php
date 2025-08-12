<?php

namespace DarkSidePro\SyliusGtmPlugin\Service;

final class GtmConfigProvider
{
    public function getContainerId(): ?string
    {
        return $_ENV['GTM_CONTAINER_ID'] ?? null;
    }
}
