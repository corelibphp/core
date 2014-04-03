<?php
/**
 * Session based storage adapters
 *
 * @since  2014-02-03
 * @author Patrick Forget <patforg@geekpad.ca>
 */

namespace Corelib\DataStore\StorageAdapter;

/**
 * Session based storage adapters
 *
 * @since  2014-02-03
 * @author Patrick Forget <patforg@geekpad.ca>
 */
class Session extends \Corelib\DataStore\StorageAdapter implements \Corelib\DataStore\StorageAdapterInterface
{

    /**
     * @var string
     */
    private $namespace = '__corelib__';

    /**
     * class constructor
     *
     * @since  2014-02-03
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    public function __construct($config) {

        parent::__construct($config);

        switch (session_status()) {
            case PHP_SESSION_ACTIVE:
                // we're good do nothing;
                break;
            case PHP_SESSION_DISABLED:
                    throw new \Corelib\DataStore\DataStoreException("Cannot init DataStore sessions are disabled");
                break;
            default:
        
                if (headers_sent()) {
                  throw new \Corelib\DataStore\DataStoreException("Cannot init DataStore session not started and headrs are already sent");
                } else {
                    session_start();
                } //if

                break;
        } //switch

        if (isset($config['namespace']) && strlen($config['namespace']) > 0) {
            $this->namespace = $config['namespace'];
        } //if

        if (!isset($_SESSION[$this->namespace])) {

            $_SESSION[$this->namespace] = array();

        } //if
        
    } // __construct()

    /**
     * @since  2014-02-03
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    public function get($key, $options = array()) {

        return (isset($_SESSION[$this->namespace][$key]) ? $_SESSION[$this->namespace][$key] : false);
        
    } // get()

    /**
     * @since  2014-02-03
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    public function set($key, $value, $options = array()) {
        $_SESSION[$this->namespace][$key] = $value; 
        return true;
    } // set()

} //  Session class
