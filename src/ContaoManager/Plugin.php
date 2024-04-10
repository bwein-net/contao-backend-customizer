<?php

declare(strict_types=1);

/*
 * This file is part of Backend Customizer for Contao Open Source CMS.
 *
 * (c) bwein.net
 *
 * @license MIT
 */

namespace Bwein\BackendCustomizer\ContaoManager;

use Bwein\BackendCustomizer\BweinBackendCustomizerBundle;
use Bwein\SystemInformation\BweinSystemInformationBundle;
use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;

class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(BweinBackendCustomizerBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class, BweinSystemInformationBundle::class]),
        ];
    }
}
