<?php
/**
 * Factory to create data stores
 *
 * @since  2014-02-03
 * @author Patrick Forget <patforg@geekpad.ca>
 */

namespace Corelib\DataStore;

/**
 * Factory to create data stores
 *
 * @since  2014-02-03
 * @author Patrick Forget <patforg@geekpad.ca>
 */
class DataStoreFactory extends \Corelib\Standard\Factory
{

    /**
     * returns a data store based on a key
     *
     * @since  2014-02-03
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    public static function getDataStoreByKey($key, $options = array()) {
        static $stores = array();

        if (!isset($stores[$key])) {
            
            $configDAO = \Corelib\Config\ConfigFactory::getConfigDAO();
            $storeConfig = $configDAO->getElementValuesById('dataStore');

            if (isset($storeConfig[$key]['adapter'])) {

                if (isset($options['namespace']) && strlen($options['namespace']) > 0) {
                    $storeConfig['namespace'] = $options['namespace'];
                } //if

                $stores[$key] = new $storeConfig[$key]['adapter']($storeConfig[$key]);
            } else {
                $stores[$key] = false;
            } //if
        } //if

        return $stores[$key];   
    } // getDataStoreByKey()


} //  DataStoreFactory class
