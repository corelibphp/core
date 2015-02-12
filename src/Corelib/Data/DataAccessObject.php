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
     * @var  \Corelib\Data\PrimaryKey
     */
    private $primaryKey = null;

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
    public function setRelationships($relationships) {
        foreach($relationships as $name => $relationship) {
            $this->setRelationship($name, $relationship);
        } //foreach
    } // setRelationships()

    /**
     * add a single relationship
     *
     * @since  2014-10-03
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    public function setRelationship($name, $relationship) {
        $this->relationships[$name] = $relationship;
    } // setRelationship()

    /**
     * get a single relationship by its namn
     *
     * @since  2014-10-03
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    public function getRelationship($name) {
        return (isset($this->relationships[$name]) ? $this->relationships[$name] : null);
    } // getRelationship()

    /**
     * @return string
     */
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    /**
     * @param string $primaryKey
     */
    public function setPrimaryKey($primaryKey)
    {
        $this->primaryKey = $primaryKey;
    }

    /**
     * class constructor
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     * @since 2013
     */
    public function __construct() {
        $this->primaryKey = new  \Corelib\Data\PrimaryKey();
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
