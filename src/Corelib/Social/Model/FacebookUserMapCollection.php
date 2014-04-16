<?php

/**
 * Holds a collection of FacebookUserMapBO
 *
 * @since  2014-04-16
 * @author Patrick Forget <patforg@geekpad.ca>
 */

namespace Corelib\Social\Model;

/**
 * Holds a collection of FacebookUserMapBO
 *
 * @since  2014-04-16
 * @author Patrick Forget <patforg@geekpad.ca>
 */
class FacebookUserMapCollection extends \Corelib\Standard\Collection
{

    /**
     * class constructor
     *
     * @since  2014-03-05
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    public function __construct() {
        parent::__construct('\Corelib\Social\Model\FacebookUserMapBO');
    } // __construct()

} //  FacebookUserMapCollection class
