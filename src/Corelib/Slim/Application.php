<?php
/**
 * Base application class
 *
 * @author Patrick Forget <patforg at geekpad.ca>
 * @since 2013
 */

namespace Corelib\Slim;

abstract class Application extends \Slim\Slim
{
 	/**
 	 * @var array
 	 */
 	private $_middleware = array();

 	/**
 	 * rereive a single middleware or a list of all middleware
 	 *
 	 * @author Patrick Forget <patforg at geekpad.ca>
 	 * @since 2013
 	 */
 	public function getMiddleware($key) {

 	    if (key_exists($key, $this->_middleware)) {
 	    	return $this->_middleware[$key];
 	    } //if

 	    return null;

 	} // getMiddleware()

 	/**
 	 * adds a middleware and register it by name
 	 *
 	 * @author Patrick Forget <patforg at geekpad.ca>
 	 * @since 2013
 	 */
 	public function registerMiddleware(\Slim\Middleware $middleware, $key) {
	    $this->_middleware[$key] = $middleware;
 		$this->add($middleware);
 	} // registerMiddleware()

    /**
     * @author Patrick Forget <patforg at geekpad.ca>
     * @since 2013
     */
    public function __construct() {
        parent::__construct();
    } // __construct()
 	
} // Application class