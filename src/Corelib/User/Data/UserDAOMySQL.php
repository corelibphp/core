<?php
/**
 * MySQL implementation of UserDAO
 *
 * @since  2014-03-05
 * @author Patrick Forget <patforg@geekpad.ca>
 */

namespace Corelib\User\Data;

/**
 * MySQL implementation of UserDAO
 *
 * @since  2014-03-05
 * @author Patrick Forget <patforg@geekpad.ca>
 */
class UserDAOMySQL extends \Corelib\Data\AccessMySQL implements \Corelib\User\Data\UserDAOInterface
{

    /**
     * class constructor
     *
     * @since  2014-03-05
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    public function __construct(\PDO $dbRead) {
        parent::__construct(
            $dbRead,
            '\Corelib\User\Model\UserBO',
            '\Corelib\User\Model\UserCollection'
        );
    } // __construct()

    /**
     * @since  2014-03-05
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    public function getById($id, $options = array()) {
        return $this->commonGetById($id, 'user', $options);        
    } // getById()

    /**
     * @since  2014-03-05
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    public function search($condition = null, $options = array()) {
        return $this->commonSearch($condition, 'user',  $options);
    } // search()

    /**
     * @since  2014-03-06
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    protected function rowFilter($row) {

        if (isset($row['disabled'])) {
            $row['disabled'] = ((int) $row['disabled'] === 1 ? true : false);
        } //if

        if (isset($row['locked'])) {
            $row['locked'] = ((int) $row['locked'] === 1 ? true : false);
        } //if

        return $row;
    } // rowFilter()

} //  UserDAOMySQL class
