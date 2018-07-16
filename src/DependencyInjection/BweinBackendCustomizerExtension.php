<?php

/*
 * This file is part of Backend Customizer for Contao Open Source CMS.
 *
 * (c) bwein.net
 *
 * @license MIT
 */

namespace Bwein\BackendCustomizer\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

/**
 * Class BweinBackendCustomizerExtension.
 */
class BweinBackendCustomizerExtension extends ConfigurableExtension
{
    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'bwein_backend_customizer';
    }

    /**
     * {@inheritdoc}
     */
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('listener.yml');
        $loader->load('services.yml');

        // Set the parameters as arguments for stylesheet generator
        if ($container->hasDefinition('bwein.backend_customizer.stylesheet_generator')) {
            $container->getDefinition('bwein.backend_customizer.stylesheet_generator')
                ->setArgument(0, $mergedConfig['header_title'])
                ->setArgument(1, $mergedConfig['header_color'])
                ->setArgument(2, $mergedConfig['env_title'])
                ->setArgument(3, $mergedConfig['env_color']);
        }
    }
}
