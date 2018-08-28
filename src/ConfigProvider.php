<?php

declare(strict_types=1);

namespace Fduarte42\ZendExpressivePlatesViewhelper;

use League\Plates\Engine;
use Zend\View\HelperPluginManager;

class ConfigProvider
{
    public function __invoke() : array
    {
        return [
            'dependencies' => $this->getDependencies(),
        ];
    }

    public function getDependencies() : array
    {
        return [
            'factories'  => [
                Engine::class => PlatesEngineFactory::class,
                HelperPluginManager::class => HelperPluginManagerFactory::class,
            ],
        ];
    }

}
