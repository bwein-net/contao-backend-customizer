<?php

declare(strict_types=1);

/*
 * This file is part of Backend Customizer for Contao Open Source CMS.
 *
 * (c) bwein.net
 *
 * @license MIT
 */

namespace Bwein\BackendCustomizer\EventListener;

use Bwein\BackendCustomizer\ParameterBag\BackendParameterBag;
use Contao\CoreBundle\ServiceAnnotation\Hook;

/**
 * @Hook("outputBackendTemplate")
 */
class OutputBackendTemplateListener
{
    private BackendParameterBag $params;

    public function __construct(BackendParameterBag $params)
    {
        $this->params = $params;
    }

    public function __invoke(string $buffer, string $template): string
    {
        if ('be_login' === $template || 'be_login_two_factor' === $template || 'be_main' === $template) {
            $buffer = str_replace(
                ['</head>', '<body '],
                [$this->getStyles().'</head>', '<body '.$this->getAttributes()],
                $buffer
            );
        }

        return $buffer;
    }

    private function getAttributes(): string
    {
        $attributes = [
            'header-title' => $this->params->getHeaderTitle(),
            'env-title' => $this->params->getEnvTitle(),
        ];

        return implode(' ', array_map(
            static fn ($v, $k) => sprintf('data-bwein-%s="%s"', $k, $v),
            $attributes,
            array_keys($attributes)
        )).' ';
    }

    private function getStyles(): string
    {
        $styles = [];

        if (!empty($headerColor = $this->params->getHeaderColor())) {
            $styles['header-color'] = $headerColor;
        }

        if (true === $this->params->isHeaderInvert()) {
            $styles['header-invert'] = '1';
        }

        if (!empty($envColor = $this->params->getEnvColor())) {
            $styles['env-color'] = $envColor;
        }

        if (!empty($mainColor = $this->params->getMainColor())) {
            $styles['main-color'] = $mainColor;
        }

        if (empty($styles)) {
            return '';
        }

        $style = implode('', array_map(
            static fn ($v, $k) => sprintf('--bwein-%s:%s;', $k, $v),
            $styles,
            array_keys($styles)
        ));

        return sprintf("<style>:root {%s}</style>\n", $style);
    }
}
