<?php
/**
 * Layout middleware
 *
 * @author Patrick Forget <patforg at geekpad.ca>
 * @since 2013
 */

namespace Corelib\Slim\Middleware;

class Layout extends \Corelib\Slim\Middleware
{

    /**
     * @var boolean
     */
    private $_enabled = true;
    
    /**
     * @var string
     */
    private $_template = 'layout.php';

    /**
     * @var Function
     */
    private $termFunction = null;

    /**
     * @var Function
     */
    private $pluralTermFunction = null;

    /**
     * retrieve value for pluralTermFunction
     *
     * @since  2014-01-30
     * @author Patrick Forget <patforg@geekpad.ca>
     *
     * @return Function current value of pluralTermFunction
     */
    public function getPluralTermFunction() {
        return $this->pluralTermFunction;
    } // getPluralTermFunction()

    /**
     * assign value for pluralTermFunction
     *
     * @since  2014-01-30
     * @author Patrick Forget <patforg@geekpad.ca>
     *
     * @param Function value to assign to pluralTermFunction
     */
    public function setPluralTermFunction($value) {
        $this->pluralTermFunction = $value;
    } // setPluralTermFunction()
    /**
     * retrieve value for termFunction
     *
     * @since  2014-01-30
     * @author Patrick Forget <patforg@geekpad.ca>
     *
     * @return Function current value of termFunction
     */
    public function getTermFunction() {
        return $this->termFunction;
    } // getTermFunction()

    /**
     * assign value for termFunction
     *
     * @since  2014-01-30
     * @author Patrick Forget <patforg@geekpad.ca>
     *
     * @param Function value to assign to termFunction
     */
    public function setTermFunction($value) {
        $this->termFunction = $value;
    } // setTermFunction()
    
    /**
     * retrieve value for template
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     *
     * @return string current value of template
     */
    public function getTemplate() {
        return $this->_template;
    } // getTemplate()
    
    /**
     * assign value for template
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     *
     * @param string value to assign to template
     */
    public function setTemplate($value) {
        $this->_template = $value;
    } // setTemplate()
    
    /**
     * retrieve value for enabled
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     *
     * @return boolean current value of enabled
     */
    public function getEnabled() {
        return $this->_enabled;
    } // getEnabled()
    
    /**
     * assign value for enabled
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     *
     * @param boolean value to assign to enabled
     */
    public function setEnabled($value) {
        $this->_enabled = $value;
    } // setEnabled()
    
	/**
     * middleware invocation method
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     * @since 2013
     */
    public function call() {

        $this->next->call();

        /* abort is layout is disabled */
        if (!$this->getEnabled()) {
            return;
        } //if

        //The Slim application
        $app = $this->app;

        //The Response object
        $res = $app->response();

        $body = $res->body(); // back up current body
        $res->body(''); // clear current boddy

        $data = array( 'body' => &$body );

        $termFunction = $this->getTermFunction();
        if ($termFunction !== null) {
            $data['dic'] = $termFunction;
        } //if
        
        $pluralTermFunction = $this->getPluralTermFunction();
        if ($pluralTermFunction !== null) {
            $data['ndic'] = $pluralTermFunction;
        } //if
        
        $app->render(
            $this->getTemplate(),
            $data
		);
    } // call()
	        
    
} // Layout class
