<?php

declare(strict_types=1);

/*
 * This file is part of Backend Customizer for Contao Open Source CMS.
 *
 * (c) bwein.net
 *
 * @license MIT
 */

namespace Bwein\BackendCustomizer\ParameterBag;

class BackendParameterBag
{
    private string $headerTitle;

    private string $headerColor;

    private bool $headerInvert;

    private string|null $mainColor;

    private string $envTitle;

    private string|null $envColor;

    public function __construct(array $config = [])
    {
        foreach ($config as $key => $value) {
            switch ($key) {
                case 'header_title':
                    $this->headerTitle = $value;
                    break;

                case 'header_color':
                    $this->headerColor = $value;
                    break;

                case 'header_invert':
                    $this->headerInvert = $value;
                    break;

                case 'env_title':
                    $this->envTitle = $value;
                    break;

                case 'env_color':
                    $this->envColor = $value;
                    break;

                case 'main_color':
                    $this->mainColor = $value;
                    break;
            }
        }
    }

    public function getHeaderTitle(): string
    {
        return $this->headerTitle;
    }

    public function getHeaderColor(): string
    {
        return $this->headerColor;
    }

    public function isHeaderInvert(): bool
    {
        return $this->headerInvert;
    }

    public function getEnvTitle(): string
    {
        if (empty($this->getEnvColor())) {
            return '';
        }

        return $this->envTitle;
    }

    public function getEnvColor(): string
    {
        if (null !== $this->envColor) {
            return $this->envColor;
        }

        $fallback = '';

        switch ($this->envTitle) {
            case 'local':
                $fallback = '#d43f3a';
                break;

            case 'dev':
                $fallback = '#eea236';
                break;

            case 'staging':
                $fallback = '#4cae4c';
                break;
        }

        return $fallback;
    }

    public function getMainColor(): string
    {
        if (null !== $this->mainColor) {
            return $this->mainColor;
        }

        $fallback = '';

        switch ($this->envTitle) {
            case 'local':
                $fallback = '#efb9b8';
                break;

            case 'dev':
                $fallback = '#fae3c3';
                break;

            case 'staging':
                $fallback = '#b5deb5';
                break;
        }

        return $fallback;
    }
}
