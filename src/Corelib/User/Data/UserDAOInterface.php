<?php
/**
 * Data access methods for user
 *
 * @since  2014-03-01
 * @author Patrick Forget <patforg@geekpad.ca>
 */

namespace Corelib\User\Data;

/**
 * Data access methods for user
 *
 * @since  2014-03-01
 * @author Patrick Forget <patforg@geekpad.ca>
 */
interface UserDAOInterface
{

    /**
     * retreive User by its unique ID
     *
     * @since  2014-03-01
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    public function getById($id, $options = array());

    /**
     * retreive a collection of users based on search criteria
     *
     * @since  2014-03-01
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    public function search($condition = null, $options = array());

} //  UserDAOInterface class
