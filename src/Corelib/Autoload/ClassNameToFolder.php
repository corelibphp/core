<?php
/**
 * Autload classes based on class name underscores are directory seperators
 *
 * @author Patrick Forget <patforg at geekpad.ca>
 * @since 2013
 */

namespace Corelib\Autoload;

class ClassNameToFolder
{

	/**
	 * @var string
	 */
	private $basePath = '';
	
    /**
     * class constructor
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     * @since 2013
     */
    public function __construct($basePath) {

    	if (!file_exists($basePath)) {
    		throw InvalidArgumentException('Base path does not exist');
    	} //if


    	$this->basePath = rtrim($basePath, '/') .'/';

    } // __construct()
    
    /**
     * default invocation
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     * @since 2013
     */
    public function __invoke($className) {
        $className = ltrim($className, '\\');
	    $fileName  = $this->basePath;
	    $namespace = '';
	    if ($lastNsPos = strripos($className, '\\')) {
	        $namespace = substr($className, 0, $lastNsPos);
	        $className = substr($className, $lastNsPos + 1);
	        $fileName  .= str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
	    } //if
	    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

	    if (file_exists($fileName)) {
	        $status = include $fileName;
	        return (bool) $status;
	    } else {
	    	return false;
	    } //if

    } // __invoke()
    

} // ClassNameToFolder class