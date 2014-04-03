<?php
/**
 * Database Authentication adapter
 *
 * @since  2014-01-30
 * @author Patrick Forget <patforg@geekpad.ca>
 */

namespace Corelib\Zend\Authentication\Adapter;

use \Zend\Authentication\Result;

/**
 * Database Authentication adapter
 *
 * @since  2014-01-30
 * @author Patrick Forget <patforg@geekpad.ca>
 */
class DB implements \Zend\Authentication\Adapter\AdapterInterface
{

    /**
     * class constructor
     *
     * @since  2014-01-30
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    public function __construct() {
        
    } // __construct()


    /**
     * authenticate method
     *
     * @since  2014-01-30
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    public function authenticate() {
        $result = new Result();

        /*
         * Result::SUCCESS
         * Result::FAILURE
         * Result::FAILURE_IDENTITY_NOT_FOUND
         * Result::FAILURE_IDENTITY_AMBIGUOUS
         * Result::FAILURE_CREDENTIAL_INVALID
         * Result::FAILURE_UNCATEGORIZED
         */

        return $result;
    } // authenticate()

} //  DB class
