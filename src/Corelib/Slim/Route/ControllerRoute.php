<?php
/**
 * Route that map to a controller and method
 *
 * @author Patrick Forget <patforg at geekpad.ca>
 * @since 2013
 */

namespace Corelib\Slim\Route;

class ControllerRoute extends \Corelib\Slim\Route
{

	/**
	 * @var string
	 */
	private $defaultControllerName = 'index';
	
	/**
	 * @var string
	 */
	private $defaultControllerAction = 'index';

	/**
	 * @var string
	 */
	private $setBaseURL = '';
	
    /**
     * @var string
     */
    private $controllerDirectoryPath = '';

    /**
     * @var \Zend\Filter\AbstractFilter
     */
    private $nameFilter = null;
    
    /**
     * retrieve value for nameFilter
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     *
     * @return \Zend\Filter\AbstractFilter current value of nameFilter
     */
    public function getNameFilter() {
        return $this->nameFilter;
    } // getNameFilter()
    
    /**
     * assign value for nameFilter
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     *
     * @param \Zend\Filter\AbstractFilter value to assign to nameFilter
     */
    public function setNameFilter($value) {
        $this->nameFilter = $value;
    } // setNameFilter()
    
    /**
     * retrieve value for controllerDirectoryPath
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     *
     * @return string current value of controllerDirectoryPath
     */
    public function getControllerDirectoryPath() {
        return $this->controllerDirectoryPath;
    } // getControllerDirectoryPath()
    
    /**
     * assign value for controllerDirectoryPath
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     *
     * @param string value to assign to controllerDirectoryPath
     */
    public function setControllerDirectoryPath($value) {
        $this->controllerDirectoryPath = $value;
    } // setControllerDirectoryPath()
    
	/**
	 * retrieve value for baseURL
	 *
	 * @author Patrick Forget <patforg at geekpad.ca>
	 *
	 * @return string current value of baseURL
	 */
	public function getBaseURL() {
	    return $this->setBaseURL;
	} // getBaseURL()
	
	/**
	 * assign value for baseURL
	 *
	 * @author Patrick Forget <patforg at geekpad.ca>
	 *
	 * @param string value to assign to baseURL
	 */
	public function setBaseURL($value) {
	    $this->setBaseURL = $value;
	} // setBaseURL()
	
	/**
	 * retrieve value for defaultControllerAction
	 *
	 * @author Patrick Forget <patforg at geekpad.ca>
	 *
	 * @return string current value of defaultControllerAction
	 */
	public function getDefaultControllerAction() {
	    return $this->defaultControllerAction;
	} // getDefaultControllerAction()
	
	/**
	 * assign value for defaultControllerAction
	 *
	 * @author Patrick Forget <patforg at geekpad.ca>
	 *
	 * @param string value to assign to defaultControllerAction
	 */
	public function setDefaultControllerAction($value) {
	    $this->defaultControllerAction = $value;
	} // setDefaultControllerAction()
	
	/**
	 * retrieve value for defaultControllerName
	 *
	 * @author Patrick Forget <patforg at geekpad.ca>
	 *
	 * @return string current value of defaultControllerName
	 */
	public function getDefaultControllerName() {
	    return $this->defaultControllerName;
	} // getDefaultControllerName()
	
	/**
	 * assign value for defaultControllerName
	 *
	 * @author Patrick Forget <patforg at geekpad.ca>
	 *
	 * @param string value to assign to defaultControllerName
	 */
	public function setDefaultControllerName($value) {
	    $this->defaultControllerName = $value;
	} // setDefaultControllerName()
	
    /**
     * returns the route pattern
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     * @since 2013
     */
    public function getRoutePattern() {
        return rtrim($this->getBaseURL(), '/') . '/(:path+)';
    } // getRoutePattern()
    
    /**
     * returns the route function
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     * @since 2013
     */
    public function getRouteFunction() {
        $app = $this->getApp();

        $controllerRoute = $this;
        
		return function ($path = array()) use ($app, $controllerRoute) {
            $controller = array_shift($path);
            if ($controller === null) {
                $controller = $controllerRoute->getDefaultControllerName();
            } //if

            $action  = array_shift($path);
            if ($action === null || $action === '') {
                $action = $controllerRoute->getDefaultControllerAction();;
            } //if

            $filter = $this->getNameFilter();

            if (is_object($filter)) {
                $controllerClassName = ucfirst($filter->filter($controller)) . 'Controller';
                $actionName = lcfirst($filter->filter($action)) .'Action';
            } else {
                $controllerClassName = ucfirst($controller) . 'Controller';
                $actionName = lcfirst($action) .'Action';
            } //if

			$fullPath = $controllerRoute->getControllerDirectoryPath() .'/'. $controllerClassName .'.php';

            if (file_exists($fullPath)) {
	            include_once($fullPath);
	        } //if

            if (
                class_exists($controllerClassName)
                && method_exists($controllerClassName, $actionName)
            ) {

                $controllerInstance = new $controllerClassName($app);

                $pathCount = sizeof($path);

                if ($pathCount > 0) {
                    $extraKeys = array();
                    $extraValues = array();
                    
                    for ($i = 0; $i < $pathCount; $i++) {
                        if ($i % 2 == 0) {
                            $extraKeys[] =& $path[$i];
                        } else {
                            $extraValues[] =& $path[$i];
                        } //if
                    } //foreach

                    if ($pathCount % 2 != 0) {
                        $extraValues[] = "";
                    } //if

                    $params = array_combine($extraKeys, $extraValues);

                    if (is_array($params)) {
                        $controllerInstance->setExtraParams($params);
                    } //if

                    $extraKeys = null;
                    $extraValues = null;
                    $pathCount = null;
                } //if

                $controllerInstance->setControllerName($controller);
                $controllerInstance->setActionName($action);
                
                $controllerInstance->$actionName();
            } else {
            	$app->notFound();
            } //if

        };
    } // getRouteFunction()
    
} // className class