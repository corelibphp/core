<?php
/**
 * base class for DataAccess PrimaryKey
 *
 * @author Patrick Forget <patforg at geekpad.ca>
 * @since 2013
 */

namespace Corelib\Data;

class PrimaryKey
{
    /**
     * @var string
     */
    private $separator;

    /**
     * @var mixed
     */
    private $key;

    public function __construct($key = null, $separator = null)
    {
        $this->key = isset($key)  ? $key : "id";
        $this->separator = isset($separator) ? $separator : "|";
    }

    /**
     * @return string
     */
    public function getSeparator()
    {
        return $this->separator;
    }

    /**
     * @param string $separator
     */
    public function setSeparator($separator)
    {
        $this->separator = $separator;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param mixed $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * Generate a primary key string out of an array of values
     *
     * @param array $values Data to build the primary key on
     * @return string       Resulting primary key
     */
    public function buildKey(array $values)
    {

        $id = array();
        if (is_array($this->getKey())) {
            if (count($this->key) == 0) {
                throw new \Corelib\Data\DataException("Primary key is empty");
            }
            foreach ($this->getKey() as $key) {
                if (!isset($values[$key])) {
                    throw new \Corelib\Data\DataException("Cannot find key: {$key} in values");
                }
                $id[] = $values[$key];
            }
        } else {
            if (strlen($this->getKey()) == 0) {
                throw new \Corelib\Data\DataException("Primary key is empty");
            }
            if (!isset($values[$this->getKey()])) {
                throw new \Corelib\Data\DataException("Cannot find key: {$this->getKey()} in values");
            }
            $id[] = $values[$this->getKey()];
        }
        $id = implode($this->getSeparator(), $id);

        return $id;
    }
}
