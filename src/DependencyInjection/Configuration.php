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

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('bwein_backend_customizer');
        $treeBuilder
            ->getRootNode()
            ->children()
                ->scalarNode('header_title')->defaultValue('')->end()
                ->scalarNode('header_color')->defaultValue('')->end()
                ->scalarNode('env_title')->defaultValue('')->end()
                ->scalarNode('env_color')->defaultValue('')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
