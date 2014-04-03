<?php
/**
 * Base class for accessing data from files
 *
 * @author Patrick Forget <patforg at geekpad.ca>
 */

namespace Corelib\Data;

/**
 * Base class for accessing data from files
 *
 * @author Patrick Forget <patforg at geekpad.ca>
 */
class AccessFile extends \Corelib\Data\DataAccessObject
{
    /**
     * @var \Zend\Filter\FilterInterface
     */
    private $keyToFileFilter = null;
    
    /**
     * class constructor
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public function __construct(\Zend\Filter\FilterInterface $keyToFileFilter) {
        parent::__construct();

        $this->keyToFileFilter = $keyToFileFilter;
    } // __construct()
    
    /**
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public function boolean($bool) {
        return $bool ? true : false;
    } // boolean()
    
    /**
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public function quote($str) {
        return $str;
    } // quote()

    /**
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public function dateTime($int) {
        return (int) $int;
    } // dateTime()

    /**
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public function date($int) {
        return strtotime(date('Y-m-d', $int));
    } // date()

    /**
     * loads an instance by it's key
     *
     * @author Patrick Forget
     * @since Fri Oct 22 16:23:04 GMT 2010 
     */
    protected function commonLoad($instanceKey, $options = array()) 
    {
        $filePath = $this->keyToFileFilter->filter($instanceKey);
        $arr = @include($filePath);
        return ( is_array($arr) ? $arr : array() );
    } // commonLoad()

} // AccessFile class
