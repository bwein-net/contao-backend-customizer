<?php

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
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('bwein_backend_customizer');

        $rootNode
            ->children()
                ->scalarNode('header_title')->defaultValue('')->end()
                ->scalarNode('header_color')->defaultValue('')->end()
                ->scalarNode('env_title')->defaultValue('')->end()
                ->scalarNode('env_color')->defaultValue('')->end()
            ->end();

        return $treeBuilder;
    }
}
