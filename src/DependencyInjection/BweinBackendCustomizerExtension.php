<?php

declare(strict_types=1);

/*
 * This file is part of Backend Customizer for Contao Open Source CMS.
 *
 * (c) bwein.net
 *
 * @license MIT
 */

namespace Bwein\BackendCustomizer\DependencyInjection;

use Bwein\BackendCustomizer\ParameterBag\BackendParameterBag;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

/**
 * Class BweinBackendCustomizerExtension.
 */
class BweinBackendCustomizerExtension extends ConfigurableExtension
{
    public function getAlias(): string
    {
        return 'bwein_backend_customizer';
    }

    protected function loadInternal(array $mergedConfig, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../../config'));
        $loader->load('services.yaml');

        $container->getDefinition(BackendParameterBag::class)->setArgument(0, $mergedConfig);
    }
}
