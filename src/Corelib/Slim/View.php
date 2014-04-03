<?php
/**
 * Base class for Slim view
 *
 * @author Patrick Forget <patforg at geekpad.ca>
 */

namespace Corelib\Slim;

/**
 * Base class for Slim view
 *
 * @author Patrick Forget <patforg at geekpad.ca>
 */
class View extends \Slim\View
{
    /**
     * @var array
     */
    private $config = array();
    
    
    /**
     * retrieve value for config
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     *
     * @return array current value of config
     */
    public function getConfig() {
        return $this->config;
    } // getConfig()
    
    /**
     * assign value for config
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     *
     * @param array value to assign to config
     */
    protected function setConfig($value) {
        $this->config = $value;
    } // setConfig()
    
    /**
     * class constructor
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public function __construct($config) {
        parent::__construct();
        $this->setConfig($config);
    } // __construct()
    
} // View class