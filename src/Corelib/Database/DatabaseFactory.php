<?php
/**
 * Creates PDO object
 *
 * @author Patrick Forget <patforg at geekpad.ca>
 */

namespace Corelib\Database;

/**
 * Creates PDO object
 *
 * @author Patrick Forget <patforg at geekpad.ca>
 */
class DatabaseFactory extends \Corelib\Standard\Factory
{


    /**
     * @var array
     */
    private static $pool = array();


    /**
     * retreive a confirgured PDO object based on key
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public static function getPDO($key = 'write') {
        
        if (!isset(self::$pool[$key])) {
            $configDAO = \Corelib\Config\ConfigFactory::getConfigDAO();
            $config = $configDAO->getElementEnvValuesById('db');
            
            $dsn = $config['databases'][$key]['dsn'];
            $user = isset($config['databases'][$key]['user']) ? $config['databases'][$key]['user'] : null;
            $pass = isset($config['databases'][$key]['pass']) ? $config['databases'][$key]['pass'] : null;
            
            $db = new \PDO($dsn, $user, $pass);
            $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            self::$pool[$key] = $db;
            
        } //if
        
        return self::$pool[$key];
    } // getPDO()

    /**
     * reset a connection from the cached connection pool
     *
     * @since  2015-02-19
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    public static function resetPDO($key = 'write') {
        if (isset(self::$pool[$key])) {
            self::$pool[$key] = null;
            unset(self::$pool[$key]);
        } //if
    } // resetPDO()

    
} // DatabaseFactory class
