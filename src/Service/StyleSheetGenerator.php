<?php

/*
 * This file is part of Backend Customizer for Contao Open Source CMS.
 *
 * (c) bwein.net
 *
 * @license MIT
 */

namespace Bwein\BackendCustomizer\Service;

use lessc;
use Symfony\Component\Filesystem\Filesystem;

class StyleSheetGenerator
{
    /**
     * @var string
     */
    private $sourceFile = __DIR__.'/../Resources/public/less/backend.less';

    /**
     * @var string
     */
    private $targetFile = __DIR__.'/../Resources/public/css/backend.css';

    /**
     * @var string
     */
    private $headerTitle;

    /**
     * @var string
     */
    private $headerColor;

    /**
     * @var string
     */
    private $envTitle;

    /**
     * @var string
     */
    private $envColor;

    /**
     * @var string
     */
    private $rootDir;

    /**
     * @var Filesystem
     */
    private $fs;

    /**
     * StyleSheetGenerator constructor.
     *
     * @param string          $headerTitle
     * @param string          $headerColor
     * @param string          $envTitle
     * @param string          $envColor
     * @param string          $rootDir
     * @param Filesystem|null $fs
     */
    public function __construct(
        string $headerTitle,
        string $headerColor,
        string $envTitle,
        string $envColor,
        string $rootDir,
        Filesystem $fs = null
    ) {
        $this->headerTitle = $headerTitle;
        $this->headerColor = $headerColor;
        $this->envTitle = $envTitle;
        $this->envColor = $envColor;
        $this->rootDir = $rootDir;
        $this->fs = $fs ? $fs : new Filesystem();
    }

    /**
     * @throws \Exception
     *
     * @return bool
     */
    public function generate()
    {
        $this->validateSource();
        $this->validateTarget();
        $this->generateStyleSheetFile();

        return true;
    }

    private function validateSource()
    {
        // Check whether the source file exists
        if (!$this->fs->exists($this->sourceFile)) {
            throw new \InvalidArgumentException(
                sprintf('The source file "%s" is invalid!', $this->sourceFile)
            );
        }
    }

    private function validateTarget()
    {
        // Check whether the target file is writable - by clearing the file
        $this->fs->dumpFile($this->targetFile, '');
    }

    /**
     * @throws \Exception
     */
    private function generateStyleSheetFile()
    {
        // Create the file
        $this->fs->dumpFile($this->targetFile, $this->compileSourceLess());
    }

    /**
     * @throws \Exception
     *
     * @return bool|int|string
     */
    private function compileSourceLess()
    {
        // Compile less source
        $less = new lessc();
        $less->setVariables($this->getLessVariables());

        return $less->compileFile($this->sourceFile);
    }

    /**
     * @return array
     */
    private function getLessVariables()
    {
        $variables = [];

        $variables['headerTitle'] = !empty($this->headerTitle) ? $this->headerTitle : 'null';
        $variables['headerColor'] = !empty($this->headerColor) ? $this->headerColor : 'null';
        $variables['envTitle'] = !empty($this->envTitle) ? $this->envTitle : 'null';
        $variables['envColor'] = !empty($this->envColor) ? $this->envColor : 'null';

        return $variables;
    }
}
