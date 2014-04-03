<?php
/**
 * File implementation of ConfigDSOInterface
 * 
 * @author Patrick Forget <patforg at geekpad.ca>
 */

namespace Corelib\Config\Data;

/**
 * File implementation of ConfigDSOInterface
 * 
 * @author Patrick Forget <patforg at geekpad.ca>
 */
class ConfigDSOFile extends \Corelib\Data\StoreFile implements ConfigDSOInterface 
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
    public function save(\Corelib\Config\Model\ConfigBO $element, $options=array()) 
    {
        $env = strtolower($element->getEnv());
        $id = $element->getId();
        $saveKey = (strlen($env) > 0 ? "{$id}.{$env}" : $id);

        $this->commonSave($element->getValues(), $saveKey);
    } // save()
    
    /**
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public function delete($id, $options=array()) 
    {
        $env = (isset($options['env']) ? strtolower($options['env']) : '');
        $deleteKey = (strlen($env) > 0 ? "{$id}.{$env}" : $id);
        $this->commonDelete($deleteKey);
    } // delete()
    
} // ConfigDSOFile class
