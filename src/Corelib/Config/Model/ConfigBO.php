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

    /**
     * @since  2014-04-24
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    static protected function getDefaultValues() {
        static $members = null;

        if ($members === null) {
            $members = array_merge(parent::getDefaultValues(),array(
                'id' => '',
                'env' => '',
                'values' => array()
            ));

        } //if

        return $members;
    } // getDefaultValues()

} // ConfigBO class
