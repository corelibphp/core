<?php
/**
 * File implementation of \Corelib\Dictionary\Model\DictionaryDAOInterface
 * 
 * @author Patrick Forget <patforg at geekpad.ca>
 */

namespace Corelib\Dictionary\Model\Data;

/**
 * File implementation of \Corelib\Dictionary\Model\DictionaryDAOInterface
 * 
 * @author Patrick Forget <patforg at geekpad.ca>
 */
class DictionaryDAOFile extends \Corelib\Model\Data\AccessFile implements DictionaryDAOInterface 
{

    /**
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public function search($condition = null, $options = array()) 
    {
        return new \Corelib\Dictionary\Model\DictionaryCollection();
    } // search()    

    /**
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public function getElementById($id, $options = array()) 
    {
        
        $values = $this->commonLoad($id);
        return new \Corelib\Dictionary\Model\DictionaryBO(array(
            'id' => $id,
            'values' => &$values
        ));
    } // getElementById()

    /**
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public function getElementValuesById($id, $options = array()) 
    {
        return $this->commonLoad($id);
    } // getElementById()

} // \Corelib\Dictionary\Model\DictionaryDAOFile class