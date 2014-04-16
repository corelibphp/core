<?php
/**
 * base class for MySQL DataAccessors
 *
 * @author Patrick Forget <patforg at geekpad.ca>
 * @since 2013
 */

namespace Corelib\Data;

abstract class AccessMySQL extends \CoreLib\Data\DataAccessObject
{
    
    /**
     * @var PDO
     */
    private $dbRead = null;
    
    /**
     * @var array
     */
    private $columnMap = null;
    
     /**
      * @var string
      */
     private $objectClass = '';

     /**
      * @var string
      */
     private $collectionClass = '';


     /**
      * retrieve value for collectionClass
      *
      * @since  2014-03-06
      * @author Patrick Forget <patforg@geekpad.ca>
      *
      * @return string current value of collectionClass
      */
     protected function getCollectionClass() {
         return $this->collectionClass;
     } // getCollectionClass()

     /**
      * assign value for collectionClass
      *
      * @since  2014-03-06
      * @author Patrick Forget <patforg@geekpad.ca>
      *
      * @param string value to assign to collectionClass
      */
     protected function setCollectionClass($value) {
         $this->collectionClass = $value;
     } // setCollectionClass()
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
    protected function getColumnMap() {
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
    protected function setColumnMap($value) {
        $this->columnMap = $value;
    } // setColumnMap()
    
    /**
     * retrieve value for dbRead
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     *
     * @return PDO current value of dbRead
     */
    protected function getDBRead() {
        return $this->dbRead;
    } // getDBRead()
    
    /**
     * assign value for dbRead
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     *
     * @param PDO value to assign to dbRead
     */
    protected function setDBRead($value) {
        $this->dbRead = $value;
    } // setDBRead()
    

    /**
     * class constructor
     */
    public function __construct(\PDO $dbRead, $objectClass, $collectionClass)
    {

        parent::__construct();

        $this->setDBRead($dbRead);

        $this->setObjectClass($objectClass);
        $this->setCollectionClass($collectionClass);

    } // __construct()

    /**
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public function boolean($bool) {
        return $bool ? 1 : 0;
    } // boolean()
    
    /**
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public function quote($str) {
        return $this->getDBRead()->quote($str);
    } // quote()

    /**
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public function dateTime($int) {
        return date('Y-m-d H:i:s', $int);
    } // dateTime()

    /**
     * @author Patrick Forget <patforg at geekpad.ca>
     */
    public function date($int) {
        return date('Y-m-d', $int);
    } // date()

    /**
     * translate order by options
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     *
     * @return string
     */
    protected function getOrder($options) {
        $order = ' ';
        if (key_exists('order', $options)) {

            $translatedOrder = array();
            $columnName = null;
            $equivalents = $this->getColumnMap();
            
            foreach (explode(',', $options['order']) as $orderDef) {
                $orderDef = trim($orderDef);
                @list($propertyName, $direction) = explode(' ', $orderDef);

                $propertyName = trim($propertyName);
                $direction = ($direction ? trim($direction) : 'ASC');
                
                $columnName = (key_exists($propertyName, $equivalents) ? $equivalents[$propertyName] : $propertyName);

                $translatedOrder[] = '`'. $columnName .'` '. $direction;
            } //foreach
            
            $order = implode(', ', $translatedSorts);

        } //if
        
        return $order;
    } // getOrder()
    
    /**
     * genereate limit statement from array of options
     *
     * @author Patrick Forget <patforg at geekpad.ca>
     * 
     * @return string limit statement for SQL
     */
    protected function getLimit($options) 
    {
        if (key_exists('limit', $options)) {
            $offset = (key_exists('offset', $options) ? $options['offset'] : '0');
            $limit = ' LIMIT ' . $offset . ', ' . $options['limit'] .' ';
        } elseif (key_exists('offset', $options)) {
            $limit = ' LIMIT ' . $options['offset'] . ', 999999999 ';
        } else {
            $limit = ' ';
        } //if
        
        return $limit;
    } // getLimit()

    /**
     * translates columns names in an SQL conditional stament (i.e. the WHERE condition)
     *
     * @author Patrick Forget
     * @since Thu Aug 21 15:42:57 GMT-05:00 2008 
     * @param string $condition the condition to translate ex. ( (A = 2) AND (B <> 'bannana') )
     * @param array $equivalents list of key value pairs to translate, the key being the string to look for and the value is the replacement
     * @return string the translated condition
     */
    protected function getTranslatedCondition($condition, &$equivalents, &$returnAsArray = false, &$options = array()) 
    {

        if ($condition == null) {
            return  ($returnAsArray ? array('1','=','1') : "1=1");
        } //if

        $condition = trim($condition);
        
        $useBackticks = (key_exists('useBacktics', $options) && $options['useBacktics'] === false ? false : true);
        
        $quotingString = ($useBackticks ? '`' : '');
        
        
        $leftParam = null;
        $rightParam = null;
        $operator = null;
        $openBracket = '';
        $closeBracket = '';
        
        $firstChar = substr($condition, 0,1);
        
        if ($firstChar == '(') {
            $openBracket = '(';
            $closeBracket = ')';
            $hadBracket = true;
            $closingIndex = $this->findClosingBracket($condition, 1);
            
            if ($closingIndex < 1 || $closingIndex === false) {
                throw new \Exception('Unable to find closing bracet in : '. $condition);
            } //if
            
            $leftParam = $this->getTranslatedCondition( substr($condition, 1, ($closingIndex-1)), $equivalents, $returnAsArray, $options);
            
            $condition = trim(substr($condition, ($closingIndex+1) ));
            
            if (strlen($condition) == 0) {
                if ($returnAsArray) {
                    
                    return ( is_string($leftParam) && key_exists($leftParam, $equivalents) ? $quotingString . $equivalents[$leftParam] . $quotingString : "`".$leftParam."`" );
                } else {
                    if (key_exists($leftParam, $equivalents)) {
                        return $openBracket . $quotingString . $equivalents[$leftParam] . $quotingString . $closeBracket;
                    } else {
                        return $openBracket . $leftParam . $closeBracket;
                    } //if
                } //if
                
            } //if
        } //if
        
        /** 
         * @todo fix so can parse conditions with strings containting "AND", "OR" and other items in the regex i.e WHERE myString = ' AND ';
         */
        if (preg_match('/(.*?)( AND | OR )(.*)/',$condition, $matches, null) || preg_match('/(.*?)(<=|>=|=|<>| LIKE | IN | NOT IN |<|>)(.*)/', $condition, $matches, null)) {
            list(,$matchLeft, $matchOp, $matchRight) = $matches;
            
            if (is_null($leftParam)) {
                $leftParam = $this->getTranslatedCondition($matchLeft, $equivalents, $returnAsArray, $options);
            } //if
            
            $operator = trim($matchOp);
            $rightParam = $this->getTranslatedCondition($matchRight, $equivalents, $returnAsArray, $options);
            
        } else {
            if (key_exists($condition, $equivalents)) {
                return  $quotingString . $equivalents[$condition]. $quotingString;
            } else {
                return $condition;
            } //if
            
        } //if
        
        if ($returnAsArray) {
            return array($leftParam, $operator, $rightParam);
        } else {
            return "$openBracket$leftParam $operator $rightParam$closeBracket";
        } //if
        
    } // getTranslatedCondition()

    /**
     * retreive items into a collection based on some criteria
     *
     * @since  2014-03-05
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    protected function commonSearch($condition, $tableName, $options) {

        if (strpos($tableName, "`") !== false) {
            throw \InvalidArgumentException("table name cannot contain backticks \"`\"");
        } //if

        $relationships = $this->getRelationships();
        $relationshipData = array();
        $relationshipsToLoad = array();
        $relationshipsToLoadInLoop = array();
        if (isset($options['loadRelationships'])) {
            if (is_array($options['loadRelationships'])) {
                foreach ($options['loadRelationships'] as $relationshipName) {
                    $relationshipsToLoad[$relationshipName] = true;
                } // foreach()
            } else {
                $relationshipsToLoad[$options['loadRelationships']] = true;
            } //if
        } //if

        $fieldsToIngnore = array();
        foreach($relationships as $relationshipName => $relationship) {
            $memberName = $relationship->getMemberName();
            $fieldsToIngnore[$memberName] = true;

            if (isset($relationshipsToLoad[$relationshipName])) {

                switch ($relationship->getType()) {
                    case \CoreLib\Data\Relationship::TYPE_ONE_TO_ONE:
                        $relationshipsToLoadInLoop[$relationshipName] = $relationship;
                        $relationshipData[$relationshipName] = array();
                        break;
                } //switch
            } //if
        } //foreach()

        ob_start();
        foreach ($this->getColumnMap() as $propName => $columnName) {
            if (isset($fieldsToIngnore[$propName])) {
                continue;
            } //if
            echo "a.`{$columnName}` as `{$propName}`,";
        } //foreach

        $columns = ob_get_contents();
        ob_end_clean();
        $columns = rtrim($columns, ', ');    

        $searchSQL = "
            SELECT 
                {$columns}
            FROM 
              `{$tableName}` a
            WHERE 
                {$this->getTranslatedCondition($condition, $this->getColumnMap())} 
            {$this->getOrder($options)} {$this->getLimit($options)} 
        ";

        $db = $this->getDBRead();
        
        $collectionClass = $this->getCollectionClass(); 
        $objectClass = $this->getObjectClass();
        $collection = new $collectionClass();

        $searchQuery = $db->query($searchSQL);
        $searchQuery->setFetchMode(\PDO::FETCH_ASSOC);

        while ($row = $this->rowFilter($searchQuery->fetch())) {

            $id = $row['id'];

            $resultBO = new $objectClass($row);

            $resultBO->setIsNew(false);

            $collection[$id] = $resultBO;

            /* one to one relationships */
            foreach ($relationshipsToLoadInLoop as $relationshipName => $relationship) {
                switch ($relationship->getType()) {
                    case \CoreLib\Data\Relationship::TYPE_ONE_TO_ONE:
                        $keyName = $relationship->getKeyMemberName();
                        $relationshipData[$relationshipName][$row[$keyName]] = $resultBO;
                        break;
                } //switch
            } //foreach 

        } //while;

        foreach (array_keys($relationshipsToLoad) as $relationshipName) {
            $relationship = $relationships[$relationshipName];

            switch ($relationship->getType()) {
                case \CoreLib\Data\Relationship::TYPE_ONE_TO_ONE:
                    $idsToLoad = array_keys($relationshipData[$relationshipName]);
                    $dao = $relationship->getDAO();
                    $idsToLoad = array_map(array($dao, 'quote'), $idsToLoad);

                    $results = $dao->search("id IN (". implode(",", $idsToLoad) .")");
                    $memberName = $relationship->getMemberName();

                    foreach($results as $resultBO) {
                        $key = $resultBO->getId();
                        $targetBO = $relationshipData[$relationshipName][$key];
                        $targetBO->setMember($memberName, $resultBO);
                        $targetBO->resetDirtyFlag($memberName);
                    } //foreach

                    break;
            } //switch
                
        } //if

        return $collection;

    } // commonSearch()

    /**
     * retreive a single item using it's unique id
     *
     * @since  2014-03-06
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    protected function commonGetById($id, $tableName, $options) {
        return current($this->commonSearch("id=". $this->quote($id), $tableName, $options));
    } // commonGetById()


    /**
     * filters row values and returns the filteredd array
     *
     * @since  2014-03-06
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    protected function rowFilter($row) {
        return $row;
    } // rowFilter()


    /**
     * finds a closing bracket
     *
     * @author Patrick Forget
     * @since Thu Aug 21 15:51:34 GMT-05:00 2008 
     */
    private function findClosingBracket($str, $offset)
    {
        
        if ($offset < 0 || $offset === false) {
            return false;
        } //if
        
        $quoteIndex = strpos($str,"'",$offset); // index of the quote opening a string '...'
        $openingBracketIndex = strpos($str,"(",$offset); // index of another set of  parenthesis starting (...)
        $closingBracketIndex = strpos($str,")",$offset); // index of the closing parenthesis )
        
        /* no closing parenthesis */
        if ($closingBracketIndex === false) {
            return false;
        } //if
        
        if ($quoteIndex !== false &&$quoteIndex >= 0 && $quoteIndex < $closingBracketIndex && $quoteIndex < $openingBracketIndex) {
            $offset = $this->findClosingQuote($str,$quoteIndex);
            $offset++; // skip closing quote
            return $this->findClosingBracket($str, $offset);
            
        /* is there another set of parenthesis inside the current one */
        } elseif ($openingBracketIndex !== false && $openingBracketIndex >= 0 && $openingBracketIndex < $closingBracketIndex) {
            $offset = $this->findClosingBracket($str, ($openingBracketIndex+1) );
            $offset++; // skip closing bracket
            
            return $this->findClosingBracket($str, $offset);
        } else {
            return $closingBracketIndex;
        } #if
        
    } // findClosingBracket()
    
    /**
     * finds matching/closing quote
     *
     * @author Patrick Forget
     * @since Thu Aug 21 15:52:24 GMT-05:00 2008 
     */
    private function findClosingQuote($str, $offset)
    {
        
        /* invalid offset */
        if ($offset < 0 || $offset === false) {
            return false;
        } //if
    
        $closingQuoteIndex = strpos($str,"'", $offset);
    
        if (substr($str, ($closingQuoteIndex-1), 1) == "\\") {
            $closingQuoteIndex = $this->findClosingQuote($str, $closingQuoteIndex);
        } //if
    
        return $closingQuoteIndex;
    } // findClosingQuote()

} // AccessMySQL class
