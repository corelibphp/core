<?php
/**
 * Abstracts the creation of Config related DAOs
 * 
 * @author Patrick Forget <patforg at geekpad.ca>
 */

namespace Corelib\Config;

/**
 * Abstracts the creation of Config related DAOs
 * 
 * @author Patrick Forget <patforg at geekpad.ca>
 */
class ConfigFactory extends \Corelib\Standard\Factory
{
    /**
     * @var string
     */
    public static $baseConfigPath = null;
    
    /**
     * creates an instance of \Corelib\Config\Data\ConfigDAOInterface
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     * @return \Corelib\Config\Data\ConfigDAOInterface instance of \Corelib\Config\Data\ConfigDAOInterface
     */
    public static function getConfigDAO() 
    {
        static $dao = null;
        if ($dao === null) {

            if (self::$baseConfigPath === null) {
                self::$baseConfigPath = APP_BASE_PATH .'/application/data/config';
            } //if

            $filter = new \Corelib\Zend\Filter\KeyToFilePath(self::$baseConfigPath);
            $filter->setSuffix('.php');
            $dao = new Data\ConfigDAOFile($filter, APPLICATION_ENV);
        } //if

        return $dao;
    } // getConfigDAO()


    /**
     * creates an instance of \Corelib\Config\Data\ConfigDSOInterface
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     * @return \Corelib\Config\Data\ConfigDSOInterface instance of \Corelib\Config\Data\ConfigDSOInterface
     */
    public static function getConfigDSO() 
    {
        static $dso = null;
        if ($dso === null) {

            if (self::$baseConfigPath === null) {
                self::$baseConfigPath = APP_BASE_PATH .'/application/data/config';
            } //if

            $filter = new \Corelib\Zend\Filter\KeyToFilePath(self::$baseConfigPath);
            $filter->setSuffix('.php');
            $dso = new Data\ConfigDSOFile($filter, APPLICATION_ENV);
        } //if

        return $dso;
    } // getConfigDAO()
    
} // ConfigFactory class
