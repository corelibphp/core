<?php
/**
 * Holds basic user information
 *
 * @since  2014-03-01
 * @author Patrick Forget <patforg@geekpad.ca>
 */

namespace Corelib\User\Model;

/**
 * Holds basic user information
 *
 * @since  2014-03-01
 * @author Patrick Forget <patforg@geekpad.ca>
 */
class UserBO extends \Corelib\Model\BusinessObject
{

    /**
     * @var array
     */
    static protected $allowedMembers = array(
        'id' => 0,
        'username' => '',
        'password' => '',
        'salt' => '',
        'disabled' => false,
        'locked' => false,
    );

} //  UserBO class
