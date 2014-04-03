<?php
/**
 * base class for Action Conrollers
 *
 * @author Patrick Forget <patforg at geekpad.ca>
 * @since 2013
 */

namespace Corelib\Slim;

class ActionController
{

	/**
	 * @var \stdClass
	 */
	protected $viewData = null;

	/**
	 * @var \Slim\Slim
	 */
	private $app = null;

	/**
	 * @var array
	 */
	private $extraParams = array();
	
	/**
	 * @var string
	 */
	private $controllerName = '';
	
	/**
	 * @var string
	 */
	private $actionName = '';
	
	/**
	 * retrieve value for actionName
	 *
	 * @author Patrick Forget <patforg at geekpad.ca>
	 *
	 * @return string current value of actionName
	 */
	public function getActionName() {
	    return $this->actionName;
	} // getActionName()
	
	/**
	 * assign value for actionName
	 *
	 * @author Patrick Forget <patforg at geekpad.ca>
	 *
	 * @param string value to assign to actionName
	 */
	public function setActionName($value) {
	    $this->actionName = $value;
	} // setActionName()
	
	/**
	 * retrieve value for controllerName
	 *
	 * @author Patrick Forget <patforg at geekpad.ca>
	 *
	 * @return string current value of controllerName
	 */
	public function getControllerName() {
	    return $this->controllerName;
	} // getControllerName()
	
	/**
	 * assign value for controllerName
	 *
	 * @author Patrick Forget <patforg at geekpad.ca>
	 *
	 * @param string value to assign to controllerName
	 */
	public function setControllerName($value) {
	    $this->controllerName = $value;
	} // setControllerName()
	
	/**
	 * retrieve value for extraParams
	 *
	 * @author Patrick Forget <patforg at geekpad.ca>
	 *
	 * @return array current value of extraParams
	 */
	public function getExtraParams() {
	    return $this->extraParams;
	} // getExtraParams()
	
	/**
	 * assign value for extraParams
	 *
	 * @author Patrick Forget <patforg at geekpad.ca>
	 *
	 * @param array value to assign to extraParams
	 */
	public function setExtraParams($value) {
	    $this->extraParams = $value;
	} // setExtraParams()
	
	/**
	 * retrieve value for app
	 *
	 * @author Patrick Forget <patforg at geekpad.ca>
	 *
	 * @return \Slim\Slim current value of app
	 */
	public function getApp() {
	    return $this->app;
	} // getApp()
	
	/**
	 * assign value for app
	 *
	 * @author Patrick Forget <patforg at geekpad.ca>
	 *
	 * @param \Slim\Slim value to assign to app
	 */
	public function setApp($value) {
	    $this->app = $value;
	} // setApp()
	
    /**
     * class constructor
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     * @since 2013
     */
    public function __construct($app) {
        $this->setApp($app);
        $this->viewData = new \stdClass();
    } // __construct()

    /**
     * render view
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     * @since 2013
     */
    public function render($viewPath = null) {
        if ($viewPath === null) {
        	$viewPath = "{$this->getControllerName()}/{$this->getActionName()}.php";
        } //if

        $this->getApp()->render($viewPath, (array) $this->viewData);
    } // render()
    
    
} // ActionController class