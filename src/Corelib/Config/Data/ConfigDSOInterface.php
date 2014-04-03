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
interface ConfigDSOInterface
{

    /**
     * Insert a new or update an existing Config
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     * @param \Corelib\Config\Model\ConfigBO $element The Config to insert or update
     * @param array $options 
     */
    public function save(\Corelib\Config\Model\ConfigBO $element, $options=array());
    
    /**
     * update the Config record to set the active flag to false
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     * @param int $element the Config to deactivate
     * @param array $options
     */
    public function delete($element, $options=array());
    
} // ConfigDSOInterface class
