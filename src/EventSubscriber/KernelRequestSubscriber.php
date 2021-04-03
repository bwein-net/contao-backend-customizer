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

use Contao\CoreBundle\Routing\ScopeMatcher;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class KernelRequestSubscriber implements EventSubscriberInterface
{
    protected $scopeMatcher;
    private $params;

    public function __construct(ScopeMatcher $scopeMatcher, ParameterBagInterface $params)
    {
        $this->scopeMatcher = $scopeMatcher;
        $this->params = $params;
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::REQUEST => 'onKernelRequest'];
    }

    public function onKernelRequest(RequestEvent $e): void
    {
        $request = $e->getRequest();

        if ($this->scopeMatcher->isBackendRequest($request)) {
            $assetFilePath = 'bundles/bweinbackendcustomizer/css/backend.css';

            if (file_exists($this->params->get('contao.web_dir').'/'.$assetFilePath)) {
                $GLOBALS['TL_CSS'][] = $assetFilePath;
            }
        }
    }
}
