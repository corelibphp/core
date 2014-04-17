<?php
/**
 * Base class for business objects
 *
 * @author Patrick Forget <patforg at geekpad.ca>
 * @since 2013
 */

namespace Corelib\Model;

abstract class BusinessObject
{

    /**
     * @var array
     */
    private $members = array();
   
    /**
     * @var array
     */
    private $dirtyMembers = array();

    /**
     * @var boolean
     */
    private $isNew = true;

    /**
     * @var array
     */
    static protected $allowedMembers = array();

    /**
     * retrieve value for isNew
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     *
     * @return boolean current value of isNew
     */
    public function getIsNew() {
        return $this->isNew;
    } // getIsNew()
    
    /**
     * assign value for isNew
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     *
     * @param boolean value to assign to isNew
     */
    public function setIsNew($value) {
        $this->isNew = $value;
    } // setIsNew()
    
    /**
     * retreive a list of all dirty flags
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public function getDirtyFlags() {
        return array_keys($this->dirtyMembers);
    } // getDirtyFlags()
    
    /**
     * clear all dirty flags
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public function resetDirtyFlags() {
        $this->dirtyMembers = array();
    } // resetDirtyFlags()
    
    /**
     * reset a single dirty flag
     *
     * @author Patrick Forget
     */
    public function resetDirtyFlag($name) 
    {
        unset($this->dirtyMembers[$name]);
    } // resetDirtyFlag()

    /**
     * retrieve value for dirtyMembers
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     *
     * @return array current value of dirtyMembers
     */
    public function getDirtyFlag($key) {
        return isset($this->dirtyMembers[$key]);
    } // getDirtyFlag()
    
    /**
     * assign value for dirtyMembers
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     *
     * @param array value to assign to dirtyMembers
     */
    public function setDirtyFlag($key) {
        $this->dirtyMembers[$key] = true;
    } // setDirtyFlag()
    
    /**
     * retrieve value for members
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     *
     * @return array current value of members
     */
    public function getMember($key) {
        if (! (isset($this->members[$key]) || array_key_exists($key, $this->members)) ) {
            throw new \BadMethodCallException("Trying to get a propety that does not exists ($key)");
        } //if
        return $this->members[$key];
    } // getMember()
   
    /**
     * assign value for members
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     *
     * @param array value to assign to members
     */
    public function setMember($key, $value) {
        if (! (isset($this->members[$key]) || array_key_exists($key, $this->members)) ) {
            throw new \BadMethodCallException("Trying to set a propety that does not exists ($key)");
        } //if
        $this->members[$key] = $value;
        $this->setDirtyFlag($key);
    } // setMember()

    /**
     * intercept get/set methods when native functions do not exist
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public function __call($methodName, $arguments) {
        $prefix = substr($methodName, 0, 3);
        if ($prefix === 'get' || $prefix === 'set') {
            $memberName = lcfirst(substr($methodName, 3));

            if ($prefix === 'get') {
                return $this->getMember($memberName);
            } else {
                return $this->setMember($memberName, current($arguments));
            } //if

        } else {
            throw new \BadMethodCallException('Method ' . $methodName . ' does not exists');
        } //if
    } // __call()
    
    /**
     * class constructor
     */
    public function __construct($members = array())
    {
        if (!is_array($members)) {
            throw new \InvalidArgumentException('Expecting $members to be an Array');
        } //if

        /* filter out values that are not allowed */
        $allowedValues = array_intersect_key($members, static::$allowedMembers);

        /* set all remaing values or use defaults if no value provided */
        $this->members = array_merge(static::$allowedMembers, $allowedValues);
        
        $this->dirtyMembers = array_map(function ($name) {return true; }, $allowedValues);

    } // __construct()

    /**
     * converts object to json
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     *
     * @return string
     */
    public function __toString() 
    {
        return \Zend\Json\Json::encode($this->toArray());
    } // __toString()
    

    /**
     * set multiple members at once
     *
     * @since  2014-03-06
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    public function setMembers(array $keyValues) {
                
        /* filter out values that are not allowed */
        $allowedValues = array_intersect_key($keyValues, static::$allowedMembers);

        foreach($allowedValues as $key => $value) {
            $this->setMember($key, $value);
        } //foreach

    } // setMembers()


    /**
     * retrieve value for allowedMembers
     *
     * @since  2014-03-06
     * @author Patrick Forget <patforg@geekpad.ca>
     *
     * @return array list of allowed members
     */
    static public function getAllowedMembers() {
        return array_keys(static::$allowedMembers);
    } // getAllowedMembers()

    /**
     * converts current object to an array
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     *
     * @return array
     */
    public function toArray() 
    {
        $properties = array();
        $class = get_class($this);

        foreach ($this->members as $propertyName => $value) {
            if ( $value instanceof \Corelib\Model\BusinessObject
                 || $value instanceof \Corelib\Model\Collection ) {
                $properties[$propertyName] = $value->toArray();
            } else {
                $properties[$propertyName] = $value;
            } //if
        } //foreach

        foreach (get_class_methods($class) as $method) {
            switch($method) {
                case 'getDirtyFlags':
                case 'getDirtyFlag':
                case 'getMember':
                    // ignore
                    break;
                default:
                    $firstThreeLetters = strtolower(substr($method, 0,3));
                    $propertyName = lcfirst(substr($method,3));
                    
                    if ($firstThreeLetters === 'get') {
                        $value = $this->{$method}();
                        if ( $value instanceof \Corelib\Model\BusinessObject
                             || $value instanceof \Corelib\Model\Collection ) {
                            $properties[$propertyName] = $value->toArray();
                        } else {
                            $properties[$propertyName] = $value;
                        } //if
                    } //if
                    break;
            } //swtich
        } //foreach
        
        return array(
            '__className__' => $class,
            'properties' => &$properties
        );
    } // toArray()


    /**
     * creates object from json string
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public static function toObject($value, $options = array()) 
    {
        $type = isset($options['type']) ? $options['type'] : 'json';
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
                    if (isset($object['properties'])) { 
                        if (is_array($object['properties'])) {
                            foreach ($object['properties'] as $propertyName => $propertyValue) {
                                $setter = 'set' . ucfirst($propertyName);
                                if (is_array($propertyValue) && isset($propertyValue['__className__'])) {
                                    /* calls static method of classType ex. LibCollection::toObject() */
                                    $propertyValue = $propertyValue['__className__']::toObject($propertyValue, array('type' => 'array'));
                                } //if

                                $class->$setter($propertyValue);
                            } //foreach
                        } //if
                    } //if
                    
                    if (!$class->getIsNew()) {
                        $class->resetDirtyFlags();
                    } //if
                } //if

            } //if
        } //if
        return $class;
    } // toObject()

} // BusinessObject class
