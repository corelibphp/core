<?php
/**
 * Defines a relationship between to Data Models
 *
 * @since  2014-03-08
 * @author Patrick Forget <patforg@geekpad.ca>
 */

/**
 * Defines a relationship between to Data Models
 *
 * @since  2014-03-08
 * @author Patrick Forget <patforg@geekpad.ca>
 */
class Relationship
{

    /**
     * one to one relationship
     */
    const TYPE_ONE_TO_ONE = 1;

    /**
     * ont to many relationship
     */
    const TYPE_ONE_TO_MANY = 2;

    /**
     * @var int
     */
    private $type = self::TYPE_ONE_TO_ONE;

    /**
     * @var \Corelib\Data\DataAccessObject
     */
    private $dao = null;

    /**
     * @var string
     */
    private $memberName = '';


    /**
     * retrieve value for memberName
     *
     * @since  2014-03-08
     * @author Patrick Forget <patforg@geekpad.ca>
     *
     * @return string current value of memberName
     */
    public function getMemberName() {
        return $this->memberName;
    } // getMemberName()

    /**
     * assign value for memberName
     *
     * @since  2014-03-08
     * @author Patrick Forget <patforg@geekpad.ca>
     *
     * @param string value to assign to memberName
     */
    protected function setMemberName($value) {
        $this->memberName = $value;
    } // setMemberName()

    /**
     * retrieve value for dao
     *
     * @since  2014-03-08
     * @author Patrick Forget <patforg@geekpad.ca>
     *
     * @return \Corelib\Data\DataAccessObject current value of dao
     */
    public function getDAO() {
        return $this->dao;
    } // getDAO()

    /**
     * assign value for dao
     *
     * @since  2014-03-08
     * @author Patrick Forget <patforg@geekpad.ca>
     *
     * @param \Corelib\Data\DataAccessObject value to assign to dao
     */
    protected function setDAO($value) {
        $this->dao = $value;
    } // setDAO()
    /**
     * retrieve value for type
     *
     * @since  2014-03-08
     * @author Patrick Forget <patforg@geekpad.ca>
     *
     * @return int current value of type
     */
    public function getType() {
        return $this->type;
    } // getType()

    /**
     * assign value for type
     *
     * @since  2014-03-08
     * @author Patrick Forget <patforg@geekpad.ca>
     *
     * @param int value to assign to type
     */
    protected function setType($value) {
        $this->type = $value;
    } // setType()

    /**
     * class constructor
     *
     * @since  2014-03-08
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    public function __construct($type, $memberName, \Corelib\Data\DataAccessObject $dao) {
        $this->setType($type);
        $this->setMemberName($memberName);
        $this->setDAO($dao);
    } // __construct()

} //  Relationship class
