<?php
/**
 * Holds config information and it's values
 *
 * @author Patrick Forget <patforg at geekpad.ca>
 */

namespace Corelib\Config\Model;
        
/**
 * Holds config information and it's values
 *
 * @author Patrick Forget <patforg at geekpad.ca>
 */
class ConfigBO extends \Corelib\Model\BusinessObject
{

    static protected $allowedMemebers = array(
        'id' => '',
        'env' => '',
        'values' => array()
    );

} // ConfigBO class
