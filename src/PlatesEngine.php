<?php

namespace Fduarte42\ZendExpressivePlatesViewhelper;

use League\Plates\Engine;
use Zend\View\HelperPluginManager;

/**
 * Template API and environment settings storage.
 */
class PlatesEngine extends Engine
{
    /**
     * Create new Engine instance.
     * @param HelperPluginManager $helperPluginManager
     * @param string $directory
     * @param string $fileExtension
     */
    public function __construct(HelperPluginManager $helperPluginManager, $directory = null, $fileExtension = 'php')
    {
        parent::__construct($directory, $fileExtension);
        $this->functions = new PlatesFunctions($helperPluginManager, $this);
    }
}
