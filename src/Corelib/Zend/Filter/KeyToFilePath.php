<?php
/**
 * Transforms a string value to a file path based on a set of rules
 *
 * @author Patrick Forget <patforg at geekpad.ca>
 */

namespace Corelib\Zend\Filter;

/**
 * Transforms a string value to a file path based on a set of rules
 *
 * @author Patrick Forget <patforg at geekpad.ca>
 */
class KeyToFilePath implements \Zend\Filter\FilterInterface
{
    /**
     * @var string
     */
    private $basePath = '';
    
    /**
     * @var string
     */
    private $suffix = '';
    
    /**
     * retrieve value for suffix
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     *
     * @return string current value of suffix
     */
    public function getSuffix() {
        return $this->suffix;
    } // getSuffix()
    
    /**
     * assign value for suffix
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     *
     * @param string value to assign to suffix
     */
    public function setSuffix($value) {
        $this->suffix = $value;
    } // setSuffix()
    
    /**
     * class constructor
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public function __construct($basePath) {
        $this->basePath = rtrim($basePath, '/');
    } // __construct()
    
    /**
     * takes a key and returns a full path to a file
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public function filter($value) {
        return "{$this->basePath}/{$value}{$this->getSuffix()}";
    } // filter()

} // KeyToFilePath class
