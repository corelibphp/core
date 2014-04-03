<?php
/**
 * Holds a collection of UserBO 
 *
 * @since  2014-03-05
 * @author Patrick Forget <patforg@geekpad.ca>
 */

namespace Corelib\User\Model;

/**
 * Holds a collection of UserBO 
 *
 * @since  2014-03-05
 * @author Patrick Forget <patforg@geekpad.ca>
 */
class UserCollection extends \Corelib\Standard\Collection
{

    /**
     * class constructor
     *
     * @since  2014-03-05
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    public function __construct() {
        parent::__construct('\Corelib\User\Model\UserBO');
    } // __construct()

} //  UserCollection class
