<?php
/**
 * Converts a string to a Javascript array
 *
 * @author Patrick Forget <patforg at geekpad.ca>
 * @since 2013
 */


namespace Corelib\Zend\Filter;

class HTMLToJavascriptArray extends \Zend\Filter\AbstractUnicode {
    
    /**
     * @var array
     */
    protected $options = array(
        'encoding' => null,
    );

    /**
     * @var array
     */
    private $replace = array(
        '<script'  => "<scr','ipt",
        '</script' => "</scr','ipt",
        "\\"        => "\\\\",
        "\n"        => '\n',
        "\r"        => '\r',
        "\t"        => '\t',
        "'"         => "\\'",
    );
    
    
    /**
     * Constructor
     *
     * @param string|array|Traversable $encodingOrOptions OPTIONAL
     */
    public function __construct($encodingOrOptions = null)
    {
        if ($encodingOrOptions !== null) {
            if (!static::isOptions($encodingOrOptions)) {
                $this->setEncoding($encodingOrOptions);
            } else {
                $this->setOptions($encodingOrOptions);
            }
        }
    }

    /**
     * @author Patrick Forget <patforg at geekpad.ca>
     * @since 2013
     */
    public function filter($value) {

        $parts = array();
        $partLength = 50;
        if ($this->options['encoding'] !== null) {

            $len = mb_strlen($value, $this->options['encoding']);
            for ($i = 0; $i < $len; $i += $partLength) {
                $parts[] = mb_substr($value, $i, $partLength, $this->options['encoding']);
            } //for

        } else {
            $parts = str_split($value, $partLength);
        } //if
        
        $searches = array_keys($this->replace);
        $replacers = array_values($this->replace);

        ob_start();
        $first = true;
        echo "[\n";
        foreach ($parts as $part) {
            if ($first === true) {
                $first = false;   
            } else {
                echo ",\n";
            } //if
            echo "    '";
            echo str_replace($searches, $replacers, $part);
            echo "'";
        } //foreach
        echo "\n]";
        $filteredValue = ob_get_contents();
        ob_end_clean();

        return $filteredValue;
    } // filter()
    
    
} // HTMLToJavascriptArray class
