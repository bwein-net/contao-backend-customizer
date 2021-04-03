<?php

declare(strict_types=1);

/*
 * This file is part of Backend Customizer for Contao Open Source CMS.
 *
 * (c) bwein.net
 *
 * @license MIT
 */

namespace Bwein\BackendCustomizer\EventSubscriber;

use Bwein\BackendCustomizer\Service\StyleSheetGenerator;
use Symfony\Bundle\FrameworkBundle\Command\AssetsInstallCommand;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ConsoleCommandSubscriber implements EventSubscriberInterface
{
    /**
     * @var StyleSheetGenerator
     */
    private $generator;

    public function __construct(StyleSheetGenerator $generator)
    {
        $this->generator = $generator;
    }

    public static function getSubscribedEvents(): array
    {
        return [ConsoleEvents::COMMAND => 'onConsoleCommand'];
    }

    public function onConsoleCommand(ConsoleCommandEvent $event): void
    {
        if (!($event->getCommand() instanceof AssetsInstallCommand)) {
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
