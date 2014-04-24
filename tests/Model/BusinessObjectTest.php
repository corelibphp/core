<?php
/**
 * Tests business object main functions
 *
 * @author Patrick Forget <patforg at geekpad.ca>
 */


class TestBO extends \Corelib\Model\BusinessObject {

    /**
     * custom getter for prop
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public function getProp() {
        return $this->getMember('prop');
    } // getProp()

    /**
     * custom setter for prop
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public function setProp($value) {
        return $this->setMember('prop', $value);
    } // setProp()

    /**
     * description
     *
     * @since  2014-04-24
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    static protected function getDefaultValues() {
        static $members = null;

        if ($members === null) {
            $members = array_merge(parent::getDefaultValues(), array(
                'name' => 'defaultName',
                'lastUpdate' => time(),
                'subTestBO' => null,
                'prop' => ''
            ));
        } //if

        return $members;
    } // getDefaultValues()


} // Something class


/**
 * Tests business object main functions
 *
 * @author Patrick Forget <patforg at geekpad.ca>
 */
class BusinessObjectTest extends PHPUnit_Framework_TestCase
{
    /**
     * test that new object has new property to true
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public function testIsNew() {
        $a = new TestBO();

        $this->assertTrue($a->getIsNew());
    } // testIsNew()
    
    /**
     * test that BO are initialized with default values
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public function testInitDefaultValues() {
        $a = new TestBO();

        $this->assertEquals($a->getName(), 'defaultName');
    } // testInitDefaultValues()
    
    /**
     * test that you can override default values in constructor
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public function testDefaultValueOverrideInConstructor() {
        $a = new TestBO(array(
            'name' => 'newName'
        ));

        $this->assertEquals($a->getName(), 'newName');
    } // testDefaultValueOverrideInConstructor()

    /**
     * test that members array can contain more than the allowed properties
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public function testAllowMembersWithInexistantProperties() {
        $a = new TestBO(array(
            'someBogusProperty' => 'does not matter',
            'name' => 'newName'
        ));

        $this->assertEquals($a->getName(), 'newName');
    } // testAllowMembersWithInexistantProperties()
    

    /**
     * test that if you pass anything but an array to the constructor it fails
     *
     * @expectedException InvalidArgumentException
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public function testInvalidMembersArgument() {
         $a = new TestBO('String Should not be valid');
    } // testInvalidMembersArgument()
    
    /**
     * test setter abstraction
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public function testSetterAbstraction() {
        $a = new TestBO();

        $a->setName('newName');

        $this->assertEquals($a->getName(), 'newName');

    } // testSetterAbstraction()
    
    /**
     * test invalid setter
     *
     * @expectedException BadMethodCallException
     * 
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public function testInvalidSetter() {
        $a = new TestBO();

        $a->setSomeBogusProperty();
    } // testInvalidSetter()

    /**
     * test invalid getter
     *
     * @expectedException BadMethodCallException
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public function testInvalidGetter() {
        $a = new TestBO();

        $a->getSomeBogusProperty();
    } // testInvalidGetter()
    
    /**
     * test that invalid properties are not included in BO
     *
     * @expectedException BadMethodCallException
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public function testIvalidPropertiesAreNotAdded() {
        $a = new TestBO(array(
            'someBogusProperty' => 'does not matter',
        ));

        $a->getSomeBogusProperty();
    } // testIvalidPropertiesAreNotAdded()

    /**
     * test that dirty flags are set when values passed to constructor
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public function testInitValuesAreDirty() {
        $a = new TestBO(array(
            'name' => 'newName'
        ));

        $this->assertTrue($a->getDirtyFlag('name'));
        $this->assertEquals(sizeof($a->getDirtyFlags()), 1);
    } // testInitValuesAreNotDirty()
    
    /**
     * test that default values are not set to dirty
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public function testDefaultValuesAreNotDirty() {
        $a = new TestBO();

        $this->assertFalse($a->getDirtyFlag('name'));
        $this->assertEquals(sizeof($a->getDirtyFlags()), 0);

    } // testDefaultValuesAreNotDirty()
    
    /**
     * test that setting a value makes the property dirty
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public function testSetMakesMemberDirty() {
        $a = new TestBO();

        $this->assertFalse($a->getDirtyFlag('name'));
        $this->assertEquals(sizeof($a->getDirtyFlags()), 0);

        $a->setName('newName');

        $this->assertTrue($a->getDirtyFlag('name'));

        $dirtyFlags = $a->getDirtyFlags();

        $this->assertEquals(sizeof($dirtyFlags), 1);

        $this->assertTrue(in_array('name', $dirtyFlags));
    } // testSetMakesMemberDirty()
    
    /**
     * test that we can reset dirty flages
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public function testResetSingleDirtyFlag() {
        $a = new TestBO();

        $a->setName('newName');
        $a->setLastUpdate(strtotime('-1hour'));

        $this->assertTrue($a->getDirtyFlag('name'));
        $this->assertTrue($a->getDirtyFlag('lastUpdate'));

        $a->resetDirtyFlag('name');

        $this->assertFalse($a->getDirtyFlag('name'));
        $this->assertTrue($a->getDirtyFlag('lastUpdate'));

    } // testResetSingleDirtyFlag()
    
    /**
     * test that we can reset all dirty flags at once
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public function testResetAllDirtyFlags() {
        $a = new TestBO();

        $a->setName('newName');
        $a->setLastUpdate(strtotime('-1hour'));

        $this->assertTrue($a->getDirtyFlag('name'));
        $this->assertTrue($a->getDirtyFlag('lastUpdate'));

        $a->resetDirtyFlags();

        $this->assertFalse($a->getDirtyFlag('name'));
        $this->assertFalse($a->getDirtyFlag('lastUpdate'));

        $this->assertEquals(sizeof($a->getDirtyFlags()), 0);
    } // testResetAllDirtyFlags()
    
    /**
     * test method abstraction doesn't allow bogus methods
     *
     * @expectedException BadMethodCallException
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public function testBogusMethodException() {
        $a = new TestBO();
        $a->bogusMethod();
    } // testBogusMethodException()


    /**
     * test that to string method returns valid json
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public function testToStringIsJSON() {
        $a = new TestBO();

        $json = $a->__toString();

        $this->assertGreaterThan(0, strlen($json));

        $decoded = \Zend\Json\Json::decode($json);

        $this->assertInstanceOf('\stdClass', $decoded);
    } // testToStringIsJSON()

    /**
     * test that json can be converted to object
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public function testToObjectFromJSON() {
        
        $json = '
            {
                "__className__":"TestBO",
                "properties":{
                    "name":"name from JSON",
                    "lastUpdate": 1378402494,
                    "subTestBO": null,
                    "prop":"custom setter property",
                    "isNew":false
                }
            }
        ';

        $a = TestBO::toObject($json, array(
            'type' => 'json'
        ));

        $this->assertInstanceOf('TestBO', $a);

        $this->assertEquals($a->getName(), 'name from JSON');
        $this->assertEquals($a->getProp(), 'custom setter property');

    } // testToObjectFromJSON()
    
    /**
     * test embeded object decode
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public function testEmbededBODecode() {
       $json = '
            {
                "__className__":"TestBO",
                "properties":{
                    "name":"name from JSON",
                    "lastUpdate": 1378402494,
                    "prop":{
                        "__className__":"TestBO",
                        "properties":{
                            "name":"prop with sub test object",
                            "lastUpdate": 1378402494,
                            "subTestBO": null,
                            "prop":"custom setter property",
                            "isNew":false
                        }
                    },
                    "subTestBO":  {
                        "__className__":"TestBO",
                        "properties":{
                            "name":"sub test",
                            "lastUpdate": 1378402494,
                            "subTestBO": null,
                            "prop":"custom setter property",
                            "isNew":false
                        }
                    },
                    "isNew":false
                }
            }
        ';

        $a = TestBO::toObject($json, array(
            'type' => 'json'
        ));

        $subTest = $a->getSubTestBO();

        $this->assertInstanceOf('TestBO', $subTest);
        $this->assertEquals($subTest->getName(), 'sub test');

        $propTest = $a->getProp();

        $this->assertInstanceOf('TestBO', $propTest);
        $this->assertEquals($propTest->getName(), 'prop with sub test object');
    } // testEmbededBODecode()

    /**
     * test to array method returns an array with expected format
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     * @since 2013
     */
    public function testToArray() {
        $a = new TestBO(array(
            'subTestBO' => new TestBO(),
            'prop' => new TestBO()
        ));


        $arr = $a->toArray();

        $this->assertTrue(is_array($arr));
        $this->assertArrayHasKey('__className__', $arr);
        $this->assertArrayHasKey('properties', $arr);
    } // testToArray()

} //  BusinessObjectTest 
