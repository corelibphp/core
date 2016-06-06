<?php
/**
 * Base class for storing data in MySQL
 *
 * @author Patrick Forget <patforg at geekpad.ca>
 * @since 2013
 */

namespace Corelib\Data;

abstract class StoreMySQL extends \Corelib\Data\DataStoreObject
{
    /**
     * @var \PDO
     */
    private $dbWrite = null;
    
    /**
     * @var array
     */
    private $columnMap = null;

     /**
      * @var string
      */
     private $objectClass = '';

     /**
      * retrieve value for objectClass
      *
      * @since  2014-03-06
      * @author Patrick Forget <patforg@geekpad.ca>
      *
      * @return string current value of objectClass
      */
     protected function getObjectClass() {
         return $this->objectClass;
     } // getObjectClass()

     /**
      * assign value for objectClass
      *
      * @since  2014-03-06
      * @author Patrick Forget <patforg@geekpad.ca>
      *
      * @param string value to assign to objectClass
      */
     protected function setObjectClass($value) {
         $this->objectClass = $value;
     } // setObjectClass()
    
    /**
     * retrieve value for columnMap
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     *
     * @return array current value of columnMap
     */
    public function getColumnMap() {

        if ($this->columnMap === null) {

            $camelToUnderscoreFunction = function ($name) {
                return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $name));
            }; // camelCase to underscore

            $objectClass = $this->getObjectClass();
            $members  = $objectClass::getAllowedMembers();
            $this->columnMap = array_combine($members, array_map($camelToUnderscoreFunction, $members));
        } //if

        return $this->columnMap;
    } // getColumnMap()
    
    /**
     * assign value for columnMap
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     *
     * @param array value to assign to columnMap
     */
    public function setColumnMap($value) {

        if ($this->columnMap === null) {

            $camelToUnderscoreFunction = function ($name) {
                return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $name));
            }; // camelCase to underscore

            $objectClass = $this->getObjectClass();
            $members  = $objectClass::getAllowedMembers();
            $this->columnMap = array_combine($members, array_map($camelToUnderscoreFunction, $members));
        } //if

        $this->columnMap = $value;
    } // setColumnMap()
    
    /**
     * retrieve value for dbWrite
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     *
     * @return \PDO current value of dbWrite
     */
    public function getDBWrite() {
        return $this->dbWrite;
    } // getDBWrite()
    
    /**
     * assign value for dbWrite
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     *
     * @param \PDO value to assign to dbWrite
     */
    public function setDBWrite($value) {
        $this->dbWrite = $value;
    } // setDBWrite()
    
    /**
     * class constructor
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     * @since 2013
     */
    public function __construct(\PDO $dbWrite, $objectClass) {
        
        parent::__construct();

        $this->setDBWrite($dbWrite);
        $this->setObjectClass($objectClass);

    } // __construct()


    /**
     * converts booleans to int
     *
     * @since  2014-03-06
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    protected function boolean($bool) {
        return ($bool === true ? 1 : 0);
    } // boolean()

    /**
     * converts timestamps to date
     *
     * @since  2014-03-06
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    protected function date($int) {
        return date('Y-m-d', $int);
    } // date()

    /**
     * converts timestamp to date time
     *
     * @since  2014-03-06
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    protected function dateTime($int) {
        return date('Y-m-d H:i:s', $int);
    } // dateTime()


    /**
     * common save method
     *
     * @author Patrick Forget
     * @since Mon Apr 20 08:32:24 EDT 2009 
     * @param \Corelib\Model\BusinessObject $item item to save
     * @param string $tableName name of the table to save to
     * @param array $options list of options for saving item
     * <ul>
     *     <li>primaryKey string primary key field, default is id</li>
     *     <li>fieldsToIgnore array of feilds that should not be saved even if dirty</li>
     *     <li>columnMap array of dirty fields as keys and column names as values, will use internal if not provided</li>
     *     <li>processMap array of with fields as keys and callback functions to apply to the values</li>
     *     <li>ignoreDuplicates boolean will ignore errors and not insert on duplicate keys</li>
     *     <li>columnFilter \Zend\Filter will apply a filter on column names</li>
     *     <li>multiLangColumn boolean will check for language at the end of column names</li>
     * </ul>
     */
    protected function commonSave(\Corelib\Model\BusinessObject $item, $tableName, $options = array())
    {
        
        
        /* DB Options */
        $doReplace =  isset($options['doReplace']) && $options['doReplace'] ? true : false;
        $ignoreDuplicates = isset($options['ignoreDuplicates']) && $options['ignoreDuplicates'] ? true : false;

        $primaryKey = isset($options['primaryKey']) ? $options['primaryKey'] : 'id';
        $primaryKeyAccessor = 'get'. ucfirst($primaryKey);
        
        /* Field Options */
        $fieldsToIgnore = isset($options['fieldsToIgnore']) ? array_flip($options['fieldsToIgnore']) : array();

        $columnMap = $this->getColumnMap();
        if (isset($options['columnMap'])) {
            $columnMap = array_merge($columnMap,  $options['columnMap']);
        } //if

        $multiLangColumn = isset($options['multiLangColumn']) && $options['multiLangColumn'] ? true : false;
        $filter = isset($options['columnFilter']) ? $options['columnFilter'] : null;

        $columnsToSave = array();
        $values = array();

        foreach ($item->getDirtyFlags() as $field) {
            
            if ( isset($fieldsToIgnore[$field]) ) {
                continue;
            } //if
            
            /* translate propeties to column names */
            $columnName = ( isset($columnMap[$field]) ? $columnMap[$field] : $field );
            
            if ($filter !== null) {
                $columnName = $filter->filter($columnName);
            } //if
            
            if ($multiLangColumn) {
                $fieldParts = explode("_", $field); // for multi lang fields like name_en
                $accessor = 'get'. ucfirst($fieldParts[0]);
                $values[$field] = $item->$accessor($fieldParts[1]);
            } else {
                $accessor = 'get'. ucfirst($field);
                $values[$field] = $item->$accessor();
            } //if
            
            $columnsToSave[$columnName] = $field;
            
        } //foreach

        $primaryKeyValue = $item->$primaryKeyAccessor();
        
        /* apply functions to certain values */
        if (isset($options['processMap']) && is_array($options['processMap'])) {
            foreach ($options['processMap'] as $field => $callback) {
                if (isset($values[$field])) {
                    $values[$field] = call_user_func($callback, $values[$field]);
                } //if
            } //foreach
        } //if
  
        if (sizeof($columnsToSave) > 0) {
            
            $db = $this->getDBWrite();
        
            $isNew = $item->getIsNew();

            if ($isNew) { // we insert
        
                $columnList ='`' . implode("`,`", array_keys($columnsToSave) ) . '`';
                $valueList = ":" . implode(", :", $columnsToSave); // creates a list of :col1, :col2
                
                $saveSQL = "
                    ". ($doReplace ? 'REPLACE' : 'INSERT') .  ( $ignoreDuplicates ? ' IGNORE'  : '') ." INTO `{$tableName}` 
                    ( $columnList ) 
                    VALUES 
                    ( $valueList )
                ";

                $saveQuery = $db->prepare($saveSQL);

            } else { // we update
                

                $columnList = '';
                foreach ($columnsToSave as $columnName => $columnKey) {
                    $columnList .= "`$columnName` = :$columnKey, ";
                } //foreach
                $columnList = rtrim($columnList , ' ,');

                $saveSQL = "
                    UPDATE ". ($ignoreDuplicates ? 'IGNORE'  : '') ."
                        `{$tableName}` 
                    SET 
                        {$columnList} 
                    WHERE 
                        `{$primaryKey}` = {$db->quote($primaryKeyValue)}
                ";
                
                $saveQuery = $db->prepare($saveSQL);

            } //if
            
            try {
                $retVal = $saveQuery->execute($values);
            } catch (PDOException $e) {
                
                $message = "Problem saving item (". get_class($item) .") "
                    ."Error: ". $e->getMessage() ." "
                    ."Query: ". $saveSQL;

                throw new Exception($messagem, 0, $e);
            } //catch
            
            if ($isNew) {
                $id = $db->lastInsertId();
                if ($id > 0) {
                    $item->setId($id);
                } //if
                $item->setIsNew(false);
            } //if
        } //if

        $item->resetDirtyFlags();
        
    } // commonSave()
    
    /**
     * common delete method
     *
     * @author Patrick Forget
     * 
     * @param integer $id id of element to delete
     * @param string $tableName name of the table to delete from
     * @param array $options 
     *     <ul>
     *         <li>purge boolean: set disabled column to 1 instead of issuing a delete<li>
     *     </ul>
     */
    protected function commonDelete($id, $tableName, $options = array() ) 
    {
        $db = $this->getDBWrite();
        
        $purge = (key_exists('purge', $options) && $options['purge'] ? true : false );

        $objectClass = $this->getObjectClass();
        $allowedMembers = array_flip($objectClass::getAllowedMembers());

        /* always purge if there is not disabled column */
        if ($purge || !isset($allowedMembers['disabled'])) {
            $deleteSQL = "
                DELETE FROM 
                    $tableName 
                WHERE 
                    id = {$db->quote($id)} 
                LIMIT 1
            ";
        } else {
            $deleteSQL = "
                UPDATE 
                    $tableName 
                SET
                    disabled = 1 
                WHERE 
                    id = {$db->quote($id)} 
                LIMIT 1
            ";
        } //if

        $db->query($deleteSQL);
        
    } // commonDelete()
    
} // StoreMySQL class
