<?php
/**
 * Defines a relationship between to Data Models
 *
 * @since  2014-03-08
 * @author Patrick Forget <patforg@geekpad.ca>
 */

namespace Corelib\Data;

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
     * e.g. one user is linked to one profile 
     * (one profile per user, profiles cannot be shared between users)
     */
    const TYPE_ONE_TO_ONE = 1;

    /**
     * one to many relationship
     * e.g. one user is linked to many contacts 
     * (one user for many contacts, contacts cannot be shared between users)
     */
    const TYPE_ONE_TO_MANY = 2;

    /**
     * many to one relationship
     * e.g. many users are linked to one preffered language 
     * (one user is linked to one language, languages can be shared between users)
     */
    const TYPE_MANY_TO_ONE = 3;

    /**
     * many to many relationship
     * e.g. many users are linked to many groups
     * (many users are linked to many groups, groups can be shared between users)
     */
    const TYPE_MANY_TO_MANY = 4;

    /**
     * @var int
     */
    private $type = self::TYPE_ONE_TO_ONE;

    /**
     * DAO to join from
     * @var \Corelib\Data\DataAccessObject
     */
    private $dao = null;

    /**
     * property that we need to set the result BO to
     * @var string
     */
    private $memberName = '';

    /**
     * property that is the target key to join on
     * @var array
     */
    private $keyMemberName = '';

    /**
     * property that cotains the result BO to retreive (many to many only)
     * @var string
     */
    private $resultMemberName = '';


    /**
     * retrieve value for resultMemberName
     *
     * @since  2014-10-02
     * @author Patrick Forget <patforg@geekpad.ca>
     *
     * @return string current value of resultMemberName
     */
    public function getResultMemberName() {
        return $this->resultMemberName;
    } // getResultMemberName()

    /**
     * assign value for resultMemberName
     *
     * @since  2014-10-02
     * @author Patrick Forget <patforg@geekpad.ca>
     *
     * @param string value to assign to resultMemberName
     */
    public function setResultMemberName($value) {
        $this->resultMemberName = $value;
    } // setResultMemberName()

    /**
     * retrieve value for keyMemberName
     *
     * @since  2014-04-16
     * @author Patrick Forget <patforg@geekpad.ca>
     *
     * @return array current value of keyMemberName
     */
    public function getKeyMemberName() {
        return $this->keyMemberName;
    } // getKeyMemberName()

    /**
     * assign value for keyMemberName
     *
     * @since  2014-04-16
     * @author Patrick Forget <patforg@geekpad.ca>
     *
     * @param array value to assign to keyMemberName
     */
    public function setKeyMemberName($value) {
        $this->keyMemberName = $value;
    } // setKeyMemberName()

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
        $this->setKeyMemberName("{$memberName}Id");
        $this->setDAO($dao);
    } // __construct()

} //  Relationship class
