<?php
namespace AlanPich\Modx\Extras;

/**
 * Class NamespaceServiceConfiguration
 *
 * Defines a configuration object capable of being used
 * if many different ways. The main point is to be a key-value
 * store for config option that allows overriding of default
 * values using modx system settings.
 *
 * You can get/set in several different ways...
 *
 * 1) As an array:
 *      $value = $conf['myKey'];
 *      $conf['myKey'] = 'newValue';
 * 2) As an invokable method
 *      $value = $conf('myKey');
 *      $conf('myKey','newValue');
 * 3) As an object
 *      $value = $conf->myKey;
 *      $conf->myKey = 'newValue';
 * 4) With getter method
 *      $value = $conf->get('myKey');
 *
 * @package AlanPich\Modx\Extras
 */
class NamespaceServiceConfiguration implements \ArrayAccess
{
    /**
     * MODX Instance
     *
     * @var  \modX
     */
    protected $modx;
    /**
     * Service Namespace
     *
     * @var string
     */
    protected $namespace;
    /**
     * Config properties
     *
     * @var array
     */
    protected $conf = array();


    /**
     * Constructor
     *
     * @param string $namespace
     * @param \modX  $modx
     * @param array  $defaults
     */
    public function __construct($namespace, \modX $modx, $defaults = array())
    {
        $this->modx = $modx;
        $this->namespace = $namespace;
        $this->conf = $defaults;
    }

    /**
     * Get configuration as json object
     *
     * @return string
     */
    public function toJSON()
    {
        $conf = new \stdClass;
        foreach ($this->conf as $key => $val) {
            $conf->$key = $this->offsetGet($key);
        }
        return json_encode($conf);
    }

    /**
     * Hydrate config params from a json-encoded string
     *
     * @param string $json
     */
    public function fromJSON($json)
    {
        $this->conf = (array)json_decode($json);
    }

    /**
     * Get a config param
     *
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        // First, check for a systemSetting override
        $sysName = $this->namespace.'.'.$key;
        $opt = $this->modx->getOption($sysName, null, null);
        if (!is_null($opt)) {
            return $opt;
        }
        if (!isset($this->conf[$key])) {
            return null;
        }
        return $this->conf[$key];
    }

    public function set($key,$value)
    {
        throw new \Exception("Method NamespaceServiceConfiguration::set has not been implemented");
    }

    /**
     * Whether a offset exists
     *
     * @param mixed $offset  An offset to check for
     * @return boolean true  on success or false on failure.
     *                       The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        return isset($this->conf[$offset]);
    }

    /**
     * Offset to retrieve
     *
     * @param mixed $offset The offset to retrieve
     * @return mixed Can return all value types
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * Offset to set
     *
     * @param mixed $offset The offset to assign the value to
     * @param mixed $value  The value to set
     * @throws \Exception
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        throw new \Exception("Method NamespaceServiceConfiguration::offsetSet has not been implemented");
    }

    /**
     * Offset to unset
     *
     * @param mixed $offset The offset to unset</p>
     * @throws \Exception
     * @return void
     */
    public function offsetUnset($offset)
    {
        throw new \Exception("Method NamespaceServiceConfiguration::offsetUnset has not been implemented");
    }

    /**
     * Allow invoking the object for get/set as well
     *
     * @param string     $key Config property key
     * @param null|mixed $val If set, use this method as a setter
     * @return void|mixed
     */
    public function __invoke($key, $val = null)
    {
        if (is_null($val)) {
            return $this->offsetGet($key);
        } else {
            $this->offsetSet($key, $val);
        }
        return null;
    }

    /**
     * Allow access as a basic object
     *
     * @param string $key
     * @param mixed  $value
     */
    public function __set($key, $value)
    {
        $this->offsetSet($key, $value);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->offsetGet($key);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function __isset($key)
    {
        $v = $this->offsetGet($key);
        return $v !== null;
    }
}