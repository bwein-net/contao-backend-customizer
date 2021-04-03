<?php

declare(strict_types=1);

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
    private $sourceFile = __DIR__.'/../../public/less/backend.less';

    /**
     * @var string
     */
    private $targetFile = __DIR__.'/../../public/css/backend.css';

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
     * @var Filesystem
     */
    private $fs;

    public function __construct(string $headerTitle, string $headerColor, string $envTitle, string $envColor, Filesystem $fs = null)
    {
        $this->headerTitle = $headerTitle;
        $this->headerColor = $headerColor;
        $this->envTitle = $envTitle;
        $this->envColor = $envColor;
        $this->fs = $fs ?: new Filesystem();
    }

    /**
     * @throws \Exception
     */
    public function generate(): bool
    {
        $this->validateSource();
        $this->validateTarget();
        $this->generateStyleSheetFile();

        return true;
    }

    private function validateSource(): void
    {
        // Check whether the source file exists
        if (!$this->fs->exists($this->sourceFile)) {
            throw new \InvalidArgumentException(sprintf('The source file "%s" is invalid!', $this->sourceFile));
        }
    }

    private function validateTarget(): void
    {
        // Check whether the target file is writable - by clearing the file
        $this->fs->dumpFile($this->targetFile, '');
    }

    /**
     * @throws \Exception
     */
    private function generateStyleSheetFile(): void
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

    private function getLessVariables(): array
    {
        $variables = [];

        $variables['headerTitle'] = !empty($this->headerTitle) ? $this->headerTitle : 'null';
        $variables['headerColor'] = !empty($this->headerColor) ? $this->headerColor : 'null';
        $variables['envTitle'] = !empty($this->envTitle) ? $this->envTitle : 'null';
        $variables['envColor'] = !empty($this->envColor) ? $this->envColor : 'null';

        return $variables;
    }
}
