<?php
/**
 * MySQL implementation of FacebookUserMapDAOInterface
 *
 * @since  2014-03-08
 * @author Patrick Forget <patforg@geekpad.ca>
 */

namespace Corelib\Social\Data;

/**
 * MySQL implementation of FacebookUserMapDAOInterface
 *
 * @since  2014-03-08
 * @author Patrick Forget <patforg@geekpad.ca>
 */
class FacebookUserMapDAOMySQL extends \Corelib\Data\AccessMySQL implements \Corelib\Social\Data\FacebookUserMapDAOInterface
{

    /**
     * class constructor
     *
     * @since  2014-03-08
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    public function __construct(\PDO $dbRead) {
        parent::__construct(
            $dbRead,
            '\Corelib\Model\FacebookUserMapBO',
            '\Corelib\Model\FacebookUserMapCollection'
        );
    } // __construct()

    /**
     * @since  2014-03-08
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    public function getById($id, $options = array()) {
        return $this->commonGetById($id, 'facebook_user_map', $options);        
    } // getById()

    /**
     * @since  2014-03-08
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    public function search($condition = null, $options = array()) {
        return $this->commonSearch($condition, 'facebook_user_map',  $options);
    } // search()

    /**
     * @since  2014-03-08
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    protected function rowFilter($row) {
        
        if (isset($row['disabled'])) {
            $row['disabled'] = ((int) $row['disabled'] === 1 ? true : false);
        } //if

        return $row;
    } // rowFilter()

} //  FacebookUserMapDAOMySQL class
