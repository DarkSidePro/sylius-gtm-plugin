<?php

namespace DarkSidePro\SyliusGtmPlugin\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class GtmConfigProvider
{
    private ParameterBagInterface $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function getContainerId(): ?string
    {
        return $this->params->get('env(GTM_CONTAINER_ID)');
    }
}
