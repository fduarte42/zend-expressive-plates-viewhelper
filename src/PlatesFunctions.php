<?php

namespace Fduarte42\ZendExpressivePlatesViewhelper;

use League\Plates\Engine;
use League\Plates\Template\Func;
use League\Plates\Template\Functions;
use LogicException;
use Zend\View\Helper\AbstractHelper;
use Zend\View\HelperPluginManager;

/**
 * @inheritdoc
 */
class PlatesFunctions extends Functions
{
    /**
     * @var HelperPluginManager
     */
    private $helperPluginManager;

    /**
     * @var Engine
     */
    private $engine;

    /**
     * @var ZendRendererAdapter
     */
    private $renderer;


    /**
     * PlatesFunctions constructor.
     * @param HelperPluginManager $helperPluginManager
     * @param Engine $engine
     */
    public function __construct(HelperPluginManager $helperPluginManager, Engine $engine)
    {
        $this->helperPluginManager = $helperPluginManager;
        $this->engine = $engine;
        $this->renderer = new ZendRendererAdapter($engine);
    }

    /**
     * @inheritdoc
     */
    public function get($name)
    {
        if ($this->exists($name)) {
            $plugin = $this->functions[$name];
        } elseif ($this->helperPluginManager->has($name)) {
            $renderer = $this->renderer;
            $plugin = new Func($name, function(...$args) use ($renderer, $name) {
                /** @var AbstractHelper $helper */
                $helper = $this->helperPluginManager->get($name);
                $helper->setView($renderer);

                if (is_callable($helper)) {
                    return $helper(...$args);
                } else {
                    return $helper;
                }
            });
        } else {
            throw new LogicException('The template function or view_helper "' . $name . '" was not found.');
        }

        return $plugin;
    }

}
