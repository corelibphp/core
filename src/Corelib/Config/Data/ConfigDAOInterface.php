<?php
/**
 * Defines basic methods to manipulate Config
 * 
 * @author Patrick Forget <patforg at geekpad.ca>
 */

namespace Corelib\Config\Data;

/**
 * Defines basic methods to manipulate Config
 * 
 * @author Patrick Forget <patforg at geekpad.ca>
 */
interface ConfigDAOInterface
{

    /**
     * Retreives a list of Config from the provided Id
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     * 
     * @param string $condition
     * @param array $options
     * @return \Corelib\Config\Model\ConfigCollection the list of Config
     */
    public function search($condition = null, $options=array());
    
    /**
     * Retreives a Config from a provided Config id
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     * 
     * @param string $id
     * @param array $options
     * @return \Corelib\Config\Model\ConfigBO Config matching provided id, if none match null will be returned
     */
    public function getElementById($id, $options=array());
    
    /**
     * Retreive values only of config 
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     * 
     * @param string $id
     * @param array $options
     * @return array values of matching Config for provided id, if none match empty array will be returned
     */
    public function getElementValuesById($id, $options=array());

    /**
     * Retreive values of config based on environment
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     * 
     * @param string $id
     * @param array $options
     * @return array values of matching Config for provided id and environment (PROD, DEV etc.), if none match empty array will be returned
     */
    public function getElementEnvValuesById($id, $options=array());

} // ConfigDAOInterface class
