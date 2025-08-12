<?php

declare(strict_types=1);

namespace DarkSidePro\SyliusGtmPlugin\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class GtmExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');
        
        // Register Twig paths
        $twigConfig = $container->getExtensionConfig('twig');
        if (!isset($twigConfig[0]['paths'])) {
            $twigConfig[0]['paths'] = [];
        }
        $twigConfig[0]['paths'][__DIR__ . '/../Resources/views'] = 'SyliusGtm';
        $container->prependExtensionConfig('twig', $twigConfig[0]);
    }
}