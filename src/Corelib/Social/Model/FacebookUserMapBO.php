<?php
/**
 * Maps Facebook users to internal users
 *
 * @since  2014-03-06
 * @author Patrick Forget <patforg@geekpad.ca>
 */

namespace Corelib\Social\Model;

/**
 * Maps Facebook users to internal users
 *
 * @since  2014-03-06
 * @author Patrick Forget <patforg@geekpad.ca>
 */
class FacebookUserMapBO extends \Corelib\Model\BusinessObject
{

    /**
     * @var array
     */
    protected static $allowedMembers = array(
        'id' => '',
        'userId' => 0,
        'user' => null,
    );

} //  FacebookUserMapBO class
