<?php

declare(strict_types=1);

namespace Fduarte42\ZendExpressivePlatesViewhelper;

use Psr\Container\ContainerInterface;
use Zend\ServiceManager\Config;
use Zend\View\HelperPluginManager;

class HelperPluginManagerFactory
{
    /**
     * @param ContainerInterface $container
     * @return HelperPluginManager
     */
    public function __invoke(ContainerInterface $container) : HelperPluginManager
    {
        $manager = new HelperPluginManager($container);

        $config = $container->has('config') ? $container->get('config') : [];
        $config = isset($config['view_helpers']) ? $config['view_helpers'] : [];

        if (! empty($config)) {
            (new Config($config))->configureServiceManager($manager);
        }

        return $manager;
    }
}
