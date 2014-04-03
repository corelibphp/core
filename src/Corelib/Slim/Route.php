<?php
/**
 * Base class for routes
 *
 * @author Patrick Forget <patforg at geekpad.ca>
 * @since 2013
 */

namespace Corelib\Slim;

abstract class Route
{
    
	/**
	 * @var \Slim\Application
	 */
	private $_app = null;
	
	/**
	 * retrieve value for app
	 *
	 * @author Patrick Forget <patforg at geekpad.ca>
	 *
	 * @return \Slim\Application current value of app
	 */
	public function getApp() {
	    return $this->_app;
	} // getApp()
	
	/**
	 * assign value for app
	 *
	 * @author Patrick Forget <patforg at geekpad.ca>
	 *
	 * @param \Slim\Application value to assign to app
	 */
	public function setApp($value) {
	    $this->_app = $value;
	} // setApp()

    /**
     * class constructor
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     * @since 2013
     */
    public function __construct($app) {
        $this->setApp($app);
    } // __construct()
    
    /**
     * returns the pattern that will match this route
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     * @since 2013
     */
    abstract public function getRoutePattern();
    
    /**
     * returns the function that will be executed when route is matched
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     * @since 2013
     */
    abstract public function getRouteFunction();
    
} // Route class