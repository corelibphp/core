<?php
/**
 * Defines basic methods to manipulate Dictionary
 * 
 * @author Patrick Forget <patforg at geekpad.ca>
 */

namespace Corelib\Dictionary\Model\Data;

/**
 * Defines basic methods to manipulate Dictionary
 * 
 * @author Patrick Forget <patforg at geekpad.ca>
 */
interface DictionaryDAOInterface
{

    /**
     * Retreives a list of Dictionary from the provided Id
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     * 
     * @param string $condition
     * @param array $options
     * @return \Corelib\Dictionary\Model\DictionaryCollection the list of Dictionary
     */
    public function search($condition = null, $options=array());
    
    /**
     * Retreives a Dictionary from a provided Dictionary id
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     * 
     * @param string $id
     * @param array $options
     * @return \Corelib\Dictionary\Model\DictionaryBO Dictionary matching provided id, if none match null will be returned
     */
    public function getElementById($id, $options=array());
    
    /**
     * Retreive values only of Dictionary 
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     * 
     * @param string $id
     * @param array $options
     * @return array values of matching Dictionary for provided id, if none match empty array will be returned
     */
    public function getElementValuesById($id, $options=array());

} // DictionaryDAOInterface class
