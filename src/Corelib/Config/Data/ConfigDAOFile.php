<?php
/**
 * File implementation of \Corelib\Config\ConfigDAOInterface
 * 
 * @author Patrick Forget <patforg at geekpad.ca>
 */

namespace Corelib\Config\Data;

/**
 * File implementation of \Corelib\Config\Data\ConfigDAOInterface
 * 
 * @author Patrick Forget <patforg at geekpad.ca>
 */
class ConfigDAOFile extends \Corelib\Data\AccessFile implements ConfigDAOInterface 
{

    /**
     * @var string
     */
    private $env = '';
    
    /**
     * class constructor
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public function __construct(\Zend\Filter\FilterInterface $keyToFileFilter, $env) {
        parent::__construct($keyToFileFilter);

        $this->env = strtolower($env);
    } // __construct()

    /**
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public function search($condition = null, $options = array()) 
    {
        return new \Corelib\Config\Model\ConfigCollection();
    } // search()    

    /**
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public function getElementById($id, $options = array()) 
    {
        
        $env = (isset($options['env']) ? strtolower($options['env']) : '');
        $loadKey = (strlen($env) > 0 ? "{$id}.{$env}" : $id);

        $values = $this->commonLoad($loadKey);
        return new \Corelib\Config\Model\ConfigBO(array(
            'id' => $id,
            'env' => $env,
            'values' => &$values
        ));
    } // getElementById()

    /**
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public function getElementValuesById($id, $options = array()) 
    {
        $env = (isset($options['env']) ? strtolower($options['env']) : '');
        $loadKey = (strlen($env) > 0 ? "{$id}.{$env}" : $id);
        return $this->commonLoad($loadKey);
    } // getElementById()

    /**
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public function getElementEnvValuesById($id, $options = array()) 
    {
        return $this->commonLoad("{$id}.{$this->env}");
    } // getElementById()
    
} // \Corelib\Config\ConfigDAOFile class
