<?php
/**
 * Manual Authentication adapter
 *
 * manually set the user id to store
 *
 * @since  2014-01-30
 * @author Patrick Forget <patforg@geekpad.ca>
 */

namespace Corelib\Zend\Authentication\Adapter;

use \Zend\Authentication\Result;

/**
 * Manual Authentication adapter
 *
 * @since  2014-01-30
 * @author Patrick Forget <patforg@geekpad.ca>
 */
class Manual implements \Zend\Authentication\Adapter\AdapterInterface
{

    /**
     * @var mixed
     */
    private $identity = false;

    /**
     * class constructor
     *
     * @since  2014-01-30
     * @author Patrick Forget <patforg@geekpad.ca>
     *
     * @param mixed $userId false will always cause a failure result and any other value will
     *     use the provided id and return a successful auth
     */
    public function __construct($identity) {
       $this->identity = $identity; 
    } // __construct()

    /**
     * authenticate method
     *
     * @since  2014-01-30
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    public function authenticate() {

        if ($this->identity === false) {
            $code = Result::FAILURE;
        } else {
            $code = Result::SUCCESS;
        } //if

        return new Result($code, $this->identity, $messages = array() );

    } // authenticate()

} //  Manual class
