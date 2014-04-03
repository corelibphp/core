<?php
/**
 * Provides a container for objects of a given class
 *
 * @author Patrick Forget <patforg at geekpad.ca>
 */

namespace Corelib\Standard;

class Collection extends \ArrayIterator
{
    
    /**
     * name of the class for elements in this collection
     * @var string
     */
    protected $classType = '';
    
    /**
     * @var int
     */
    private $fullCollectionCount = null;
    
    /**
     * Retreive value for fullCollectionCount
     *
     * @author Patrick Forget
     * @since Mon Feb 09 13:21:55 GMT-05:00 2009
     * @return int value for fullCollectionCount
     */
    public function getFullCollectionCount() 
    {
        return ( $this->fullCollectionCount === null ? $this->count() : $this->fullCollectionCount);
    } // getFullCollectionCount()
    
    /**
     * Assign value to fullCollectionCount
     *
     * @author Patrick Forget
     * @since Mon Feb 09 13:21:55 GMT-05:00 2009 
     * @param int $value value to assign to fullCollectionCount
     */
    public function setFullCollectionCount ($value)
    {
        $this->fullCollectionCount = $value;
    } // setFullCollectionCount()
    
    /**
     * class constructor
     */
    public function __construct($classType)
    {
        $this->classType = $classType;
    } // __construct()
    
    /**
     * @author Patrick Forget
     * @since Wed Jul 16 10:08:44 GMT-05:00 2008 
     * @throws \InvalidArgumentException
     */
    public function append($value) 
    {
        if ($value instanceof $this->classType) {
            parent::append($value);
        } elseif (is_object($value)) {            
            throw new \InvalidArgumentException("Expected class of type {$this->classType} ". get_class($value) . " was passed");
        } else {
            throw new \InvalidArgumentException("Expected class of type {$this->classType} non object value ($value) was passed");
        } //if
    } // append()
    
    /**
     * @author Patrick Forget
     * @since Wed Jul 16 10:17:56 GMT-05:00 2008 
     * @throws \InvalidArgumentException
     */
    public function offsetSet($index, $value) 
    {
        if ($value instanceof $this->classType) {
            parent::offsetSet($index, $value);
        } elseif (is_object($value)) {
            throw new \InvalidArgumentException("Expected class of type {$this->classType} object of type ". get_class($value) . " was passed");
        } else {
            throw new \InvalidArgumentException("Expected class of type {$this->classType} non object value ($value) was passed");
        } //if
    } // offsetSet()
    
    /**
     * append an array to collection
     *
     * @author Patrick Forget
     * @since Tue Nov 17 01:55:13 GMT 2009 
     */
    public function appendArray(&$array, $preserveKeys = true) 
    {
        if ($preserveKeys) {
            foreach ($array as $key => $value) {
                $this->offsetSet($key, $value);  
            } //foreach
        } else {
            foreach ($array as $value) {
                $this->append($value);  
            } //foreach
        } //if
    } // appendArray()
    
    
    /**
     * converts object to json
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public function __toString() 
    {
        return \Zend\Json\Json::encode($this->toArray());
    } // __toString()
    
    /**
     * converts a coolection to an array
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public function toArray() 
    {
        $class = get_class($this);
        $properties = array();
        
        foreach ($this as $key => $value) {
            if ($value instanceof \Corelib\Model\BusinessObject) {
                $properties[$key] = $value->toArray();
            } //if
        } //foreach
        
        return array(
            '__className__' => $class,
            'properties' => &$properties
        );
    } // toArray()

    /**
     * converts a json representation back to a collection
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public static function toObject($value, $options = array()) 
    {
        $type = key_exists('type', $options) ? $options['type'] : 'json';
        $class = null;
        if ($type == 'json' || $type == 'array') {
            
            if ($type == 'json') {
                $object = \Zend\Json\Json::decode($value, \Zend\Json\Json::TYPE_ARRAY);
            } else {
                $object =& $value;
            }//if

            if (is_array($object) && isset($object['__className__'])) {

                if (class_exists($object['__className__'])) {
                    
                    $class = new $object['__className__']();
                    if ($class instanceof \Corelib\Standard\Collection && key_exists('properties', $object)) { 
                        if (is_array($object['properties'])) {
                            foreach ($object['properties'] as $propertyName => $propertyValue) {
                                if (is_array($propertyValue) && key_exists('__className__', $propertyValue)) {
                                    /* calls static method of classType ex. Collection::toObject() */
                                    $propertyValue = $propertyValue['__className__']::toObject($propertyValue, array('type' => 'array'));
                                } //if

                                $class[$propertyName] = $propertyValue;
                            } //foreach
                        } //if
                    } //if
                    
                } //if

            } //if
        } //if
        return $class;
    } // toObject()

    
} // Collection class