<?php
/**
 * Base class that stores data into files
 *
 * @author Patrick Forget <patforg at geekpad.ca>
 */

namespace Corelib\Data;

/**
 * Base class that stores data into files
 *
 * @author Patrick Forget <patforg at geekpad.ca>
 */
class StoreFile extends \Corelib\Data\DataStoreObject
{
    /**
     * @var \Zend\Filter\FilterInterface
     */
    private $keyToFileFilter = null;

    /**
     * class constructor
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public function __construct(\Zend\Filter\FilterInterface $keyToFileFilter) {
        parent::__construct();

        $this->keyToFileFilter = $keyToFileFilter;
    } // __construct()

    /**
     * saves an array into a file
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    protected function commonSave($values, $instanceKey, $options = array()) 
    {
        
        $filename = $this->keyToFileFilter->filter($instanceKey);
        
        $lastUpdate = date('Y-m-d H:i:s');

        $dirname = dirname($filename);
        if (!file_exists($dirname)) {
            $dirCreationSuccess = @mkdir($dirname, 0775, true);
            if ($dirCreationSuccess === false) {
                throw new \Exception("Unable to create missing directory {$dirname}");
            } //if
        } //if

        $filePointer = fopen($filename, 'w');
        if ($filePointer === false) {
            throw new \Exception("Unable to open file {$filename}");
        } //if

        ob_start();
        echo <<<PHP
<?php
/**
 * Script Generated file 
 * @since $lastUpdate
 */
return array(

PHP;
        $this->outputArray($values);        
        echo ");\n";
        
        fwrite($filePointer, ob_get_contents());
        ob_end_clean();
        
        chmod($filename, 0775);
        fclose($filePointer);
        
    } // commonSave()

    /**
     * deletes config file
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    protected function commonDelete($instanceKey, $options = array()) {
        $filename = $this->keyToFileFilter->filter($instanceKey);

        $success = false;
        if (file_exists($filename)) {
            $success = @unlink($filename);
        } //if

        return $success;
    } // commonDelete()

    /**
     * output arrau
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    private function outputArray(&$arr, $level = 1)
    {
        static $searchArray = array("'","$");
        static $replaceArray = array("\'","\$");
        $pad = str_repeat('    ', $level);
        foreach ($arr as $key => $value) {
            
            $termKey = str_replace($searchArray, $replaceArray,$key); // add slash to single quotes
            
            if (is_array($value)) {
                echo "{$pad}'$termKey' => array(\n";
                $this->outputArray($value, $level+1);
                echo "{$pad}),\n";
            } elseif (is_bool($value)) {
                echo "{$pad}'$termKey' => ". ($value ? 'true' : 'false') .",\n";
            } else {
                $termValue = str_replace(array("\\", '"') ,array("\\\\", '\"') ,$value); // add slash to double quotes
                echo "{$pad}'$termKey' => \"$termValue\",\n";
            } //if
               
        } //foreach
    } // outputArray()
    
} // StoreFile class
