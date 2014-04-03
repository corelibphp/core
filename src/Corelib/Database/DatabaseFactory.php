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
     * retreive a confirgured PDO object based on key
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public static function getPDO($key = 'write') {
        static $pool = array();
        
        if (!isset($pool[$key])) {
            $configDAO = \Corelib\Config\ConfigFactory::getConfigDAO();
            $config = $configDAO->getElementEnvValuesById('db');
            
            $dsn = $config['databases'][$key]['dsn'];
            $user = isset($config['databases'][$key]['user']) ? $config['databases'][$key]['user'] : null;
            $pass = isset($config['databases'][$key]['pass']) ? $config['databases'][$key]['pass'] : null;
            
            $db = new \PDO($dsn, $user, $pass);
            $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $pool[$key] = $db;
            
        } //if
        
        return $pool[$key];
    } // getPDO()
    
} // DatabaseFactory class
