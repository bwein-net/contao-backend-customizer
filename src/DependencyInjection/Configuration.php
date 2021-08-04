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
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->scalarNode('header_title')
                    ->info('Adds a title to the header next to the logo.')
                    ->defaultValue('')
                ->end()
                ->scalarNode('header_color')
                    ->info('Configures the header color.')
                    ->defaultValue('')
                ->end()
                ->booleanNode('header_invert')
                    ->info('Inverts the elements of the header.')
                    ->defaultValue(false)
                ->end()
                ->scalarNode('env_title')
                    ->info('Specifies the title of the environment badge.')
                    ->defaultValue('')
                ->end()
                ->scalarNode('env_color')
                    ->info('Configures the color of the environment badge.')
                    ->defaultValue(null)
                ->end()
                ->scalarNode('main_color')
                    ->info('Configures the background color of the main container.')
                    ->defaultValue(null)
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
