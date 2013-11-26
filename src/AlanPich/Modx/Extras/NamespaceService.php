<?php
namespace AlanPich\Modx\Extras;

use modX;

/**
 * Class NamespaceService
 *
 * Base class for defining a MODX service for a package namespace.
 * Handles configuration and instantiation, leaving you free to
 * focus on your custom API
 *
 * @package AlanPich\Modx\Extras
 */
abstract class NamespaceService
{
    /** @var modX  */
    public $modx;

    /** @var string */
    public $namespace;

    /**
     * Constructor
     *
     * @param modX  $modx
     * @param array $config
     * @throws \Exception
     */
    public function __construct(modX $modx, $config = array())
    {
        $this->modx = $modx;
        if(!$this->namespace){
            throw new \Exception(__CLASS__."->namespace is not defined");
        }
        $this->config = new NamespaceServiceConfiguration($this->namespace,$modx,$this->getDefaultConfig());
    }

    /**
     * Default configuration properties for this service. All properties can be
     * overridden using MODX system settings.
     *
     * When retrieving settings from this service (using property name $key),
     * the following paths are checked:
     *
     *  1) Look for a modx system setting called {$namespace}.{$key}
     *  2) Check service config default values
     *
     * @return array
     */
    protected function getDefaultConfig()
    {
        return array();
    }



} 