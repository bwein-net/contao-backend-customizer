<?php

/*
 * This file is part of Backend Customizer for Contao Open Source CMS.
 *
 * (c) bwein.net
 *
 * @license MIT
 */

namespace Bwein\BackendCustomizer\EventListener;

use Bwein\BackendCustomizer\Service\StyleSheetGenerator;
use Symfony\Bundle\FrameworkBundle\Command\AssetsInstallCommand;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Style\SymfonyStyle;

class AssetsInstallCommandListener
{
    /**
     * @var StyleSheetGenerator
     */
    private $generator;

    /**
     * AssetsInstallCommandListener constructor.
     *
     * @param StyleSheetGenerator $generator
     */
    public function __construct(StyleSheetGenerator $generator)
    {
        $this->generator = $generator;
    }

    /**
     * @param ConsoleCommandEvent $event
     */
    public function onConsoleCommand(ConsoleCommandEvent $event)
    {
        if (!(($command = $event->getCommand()) instanceof AssetsInstallCommand)) {
            return;
        }

        $input = $event->getInput();
        $output = $event->getOutput();
        $io = new SymfonyStyle($input, $output);

        try {
            $this->generator->generate();
            $io->success('Stylesheet for Backend Customizer was generated.');
        } catch (\Exception $e) {
            $io->warning(sprintf('Stylesheet for Backend Customizer could not be generated: "%s"', $e->getMessage()));
        }
    }
}
