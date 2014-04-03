<?php
/**
 * Data access base class
 *
 * @author Patrick Forget <patforg at geekpad.ca>
 * @since 2013
 */

namespace Corelib\Data;

abstract class DataAccessObject
{

    /**
     * @var array
     */
    private $relationships = array();

    /**
     * retrieve value for relationships
     *
     * @since  2014-03-08
     * @author Patrick Forget <patforg@geekpad.ca>
     *
     * @return array current value of relationships
     */
    public function getRelationships() {
        return $this->relationships;
    } // getRelationships()

    /**
     * assign value for relationships
     *
     * @since  2014-03-08
     * @author Patrick Forget <patforg@geekpad.ca>
     *
     * @param array value to assign to relationships
     */
    public function setRelationships($value) {
        $this->relationships = $value;
    } // setRelationships()
    
    /**
     * class constructor
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     * @since 2013
     */
    public function __construct() {
        
    } // __construct()
    
    /**
     * converts boolean to a format supported by storage
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     * 
     * @return string representation of value depending on storage type
     */
    abstract public function boolean($bool);
    
    /**
     * quotes a string to a format supported by storage
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     * 
     * @return string representation of value depending on storage type
     */
    abstract public function quote($str);

    /**
     * converts timestamps into date and time to a format supported by storage
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     * 
     * @return string representation of value depending on storage type
     */
    abstract public function dateTime($int);

    /**
     * converts timestamps into date only to a format supported by storage
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     *
     * @return string representation of value depending on storage type
     */
    abstract public function date($int);
    
} // DataAccessObject class
