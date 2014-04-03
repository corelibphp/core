<?php
/**
 * Contains a list of \Corelib\Config\ConfigBO
 * 
 * @author Patrick Forget <patforg at geekpad.ca>
 */

namespace Corelib\Config;

/**
 * Contains a list of \Corelib\Config\ConfigBO
 * 
 * @author Patrick Forget <patforg at geekpad.ca>
 */
class ConfigCollection extends \Corelib\Standard\Collection 
{
    
    /**
     * class constructor
     */
    public function __construct()
    {
        parent::__construct('\Corelib\Config\ConfigBO');
    } // __construct()
    
} // ConfigCollection class