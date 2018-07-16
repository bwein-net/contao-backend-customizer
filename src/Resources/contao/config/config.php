<?php

if (TL_MODE == 'BE') {
    Contao\System::getContainer()->get('bwein.backend_customizer.listener.stylesheet')->addStylesheet(
        'bundles/bweinbackendcustomizer/css/backend.css'
    );
}
