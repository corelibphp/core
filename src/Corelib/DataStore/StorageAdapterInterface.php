<?php
/**
 * Defines methods supported by StorageAdapters
 *
 * @since  2014-02-03
 * @author Patrick Forget <patforg@geekpad.ca>
 */

namespace Corelib\DataStore;

/**
 * Defines methods supported by StorageAdapters
 *
 * @since  2014-02-03
 * @author Patrick Forget <patforg@geekpad.ca>
 */
interface StorageAdapterInterface
{

    /**
     * get a stored object by it's key
     *
     * @since  2014-02-03
     * @author Patrick Forget <patforg@geekpad.ca>
     * @return mixed value stored in key or false if not set
     */
    public function get($key, $options = array());

    /**
     * store an object under specified key
     *
     * @since  2014-02-03
     * @author Patrick Forget <patforg@geekpad.ca>
     * @return boolean true if save was successful 
     */
    public function set($key, $value, $options = array());

} //  StorageAdapterInterface class
