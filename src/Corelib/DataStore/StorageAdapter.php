<?php
/**
 * Base class for storage adapters
 *
 * @since  2014-02-03
 * @author Patrick Forget <patforg@geekpad.ca>
 */

namespace Corelib\DataStore;

/**
 * Base class for storage adapters
 *
 * @since  2014-02-03
 * @author Patrick Forget <patforg@geekpad.ca>
 */
abstract class StorageAdapter
{

    /**
     * @var array
     */
    protected $config = array();

    /**
     * class constructor
     *
     * @since  2014-02-03
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    public function __construct($config) {
       $this->config = $config; 
    } // __construct()

} //  StorageAdapter class
