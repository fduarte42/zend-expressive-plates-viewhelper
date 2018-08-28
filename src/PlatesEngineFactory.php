<?php

declare(strict_types=1);

namespace Fduarte42\ZendExpressivePlatesViewhelper;

use function class_exists;
use function get_class;
use function gettype;
use function is_array;
use function is_object;
use function is_string;
use function sprintf;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Helper;
use Zend\Expressive\Plates\Exception\InvalidExtensionException;
use Zend\Expressive\Plates\Extension\EscaperExtension;
use Zend\Expressive\Plates\Extension\EscaperExtensionFactory;
use Zend\Expressive\Plates\Extension\UrlExtension;
use Zend\Expressive\Plates\Extension\UrlExtensionFactory;
use Zend\View\HelperPluginManager;


/**
 * @inheritdoc
 */
class PlatesEngineFactory extends \Zend\Expressive\Plates\PlatesEngineFactory
{
    public function __invoke(ContainerInterface $container) : Engine
    {
        $config = $container->has('config') ? $container->get('config') : [];
        $config = isset($config['plates']) ? $config['plates'] : [];

        // Create the engine instance:
        $engine = new PlatesEngine($container->get(HelperPluginManager::class));
        $this->injectUrlExtension($container, $engine);
        $this->injectEscaperExtension($container, $engine);

        if (isset($config['extensions']) && is_array($config['extensions'])) {
            $this->injectExtensions($container, $engine, $config['extensions']);
        }

        return $engine;
    }

    /**
     * @inheritdoc
     */
    private function injectUrlExtension(ContainerInterface $container, PlatesEngine $engine) : void
    {
        if ($container->has(UrlExtension::class)) {
            $engine->loadExtension($container->get(UrlExtension::class));
            return;
        }

        // If the extension was not explicitly registered, load it only if both helpers were registered
        if (! $container->has(Helper\UrlHelper::class) || ! $container->has(Helper\ServerUrlHelper::class)) {
            return;
        }

        $extensionFactory = new UrlExtensionFactory();
        $engine->loadExtension($extensionFactory($container));
    }

    /**
     * @inheritdoc
     */
    private function injectEscaperExtension(ContainerInterface $container, PlatesEngine $engine) : void
    {
        if ($container->has(EscaperExtension::class)) {
            $engine->loadExtension($container->get(EscaperExtension::class));
            return;
        }

        $extensionFactory = new EscaperExtensionFactory();
        $engine->loadExtension($extensionFactory($container));
    }

    /**
     * @inheritdoc
     */
    private function injectExtensions(ContainerInterface $container, PlatesEngine $engine, array $extensions) : void
    {
        foreach ($extensions as $extension) {
            $this->injectExtension($container, $engine, $extension);
        }
    }

    /**
     * @inheritdoc
     */
    private function injectExtension(ContainerInterface $container, PlatesEngine $engine, $extension) : void
    {
        if ($extension instanceof ExtensionInterface) {
            $engine->loadExtension($extension);
            return;
        }

        if (! is_string($extension)) {
            throw new InvalidExtensionException(sprintf(
                '%s expects extension instances, service names, or class names; received %s',
                __CLASS__,
                (is_object($extension) ? get_class($extension) : gettype($extension))
            ));
        }

        if (! $container->has($extension) && ! class_exists($extension)) {
            throw new InvalidExtensionException(sprintf(
                '%s expects extension service names or class names; "%s" does not resolve to either',
                __CLASS__,
                $extension
            ));
        }

        $extension = $container->has($extension)
            ? $container->get($extension)
            : new $extension();

        if (! $extension instanceof ExtensionInterface) {
            throw new InvalidExtensionException(sprintf(
                '%s expects extension services to implement %s ; received %s',
                __CLASS__,
                ExtensionInterface::class,
                (is_object($extension) ? get_class($extension) : gettype($extension))
            ));
        }

        $engine->loadExtension($extension);
    }
}
