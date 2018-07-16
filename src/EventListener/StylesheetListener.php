<?php

/*
 * This file is part of Backend Customizer for Contao Open Source CMS.
 *
 * (c) bwein.net
 *
 * @license MIT
 */

namespace Bwein\BackendCustomizer\EventListener;

use Symfony\Component\Filesystem\Filesystem;

class StylesheetListener
{
    /**
     * @var Filesystem
     */
    private $fs;

    /**
     * @var string
     */
    private $webDir;

    /**
     * StylesheetListener constructor.
     *
     * @param Filesystem $fs
     * @param $webDir
     */
    public function __construct(
        Filesystem $fs,
        $webDir
    ) {
        $this->fs = $fs ? $fs : new Filesystem();
        $this->webDir = $webDir;
    }

    public function addStylesheet($assetFilePath)
    {
        if ($this->fs->exists($this->webDir.'/'.$assetFilePath)) {
            $GLOBALS['TL_CSS'][] = $assetFilePath;
        }
    }
}
