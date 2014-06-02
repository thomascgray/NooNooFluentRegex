<?php

namespace NooNoo;

/**
 * Regular expression builder
 *
 * This class uses simple & fluent English words to build regular expression
 * rules
 *
 * @package     NooNoo
 * @author      tomgray15
 * @version     0.1.1
 *
 */
class Regex
{
    const DELIMITER                 = '#';
    const ZERO_OR_MORE              = '*';
    const ONE_OR_MORE               = '+';
    const ZERO_OR_ONE               = '?';

    /**
     * The current regex string
     * @var string
     */
    protected $expression;


    protected $multiple_value       = null;
    protected $multiple_value_limit = null;

    //for the cheeky hardcoded ones
    protected $multiple_string      = null;

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->expression = '';
    }

    /**
     * The base action of adding a string/piece of regex/text etc. to the current expression
     *
     * @param  string $string The actual chunk of regex/text/whatever
     * @param  string $name   Optional name for this capture group
     * @return Regex
     */
    public function add($string, $name = null)
    {

        $this->expression .= '(';

        if (isset($name)) {
            $this->expression .= '?P<' . $name . '>';
        }

        $this->expression .= $string;

        // Handle expression opening bracket nesting
        if (isset($this->multiple_value)) {
            $this->expression .= '{' . $this->multiple_value;

            $this->expression .= isset($this->multiple_value_limit)
                ? ',' . $this->multiple_value_limit . '}'
                : '}';

            $this->multiple_value       = null;
            $this->multiple_value_limit = null;
        }

        // Handle expression closing bracket nesting
        switch ($this->multiple_string) {

            case self::ZERO_OR_ONE:
                $this->expression .= ')' . $this->multiple_string;
                break;

            case self::ONE_OR_MORE:
            case self::ZERO_OR_MORE:
                $this->expression .= $this->multiple_string . ')';
                break;

            default:
                $this->expression .= $this->multiple_string . ')';
                break;
        }

        $this->multiple_string = '';

        return $this;
    }

    public function multiple($count)
    {
        $this->multiple_value = $count;

        return $this;
    }

    /**
     * Defines the next item as occuring between the two number of times
     *
     * @param  int $count_low  the minimum number of times the next chunk can appear
     * @param  int $count_high the maximum number of times the next chunk can appear
     * @return Regex
     */
    public function between($count_low, $count_high)
    {
        $this->multiple_value       = $count_low;
        $this->multiple_value_limit = $count_high;

        return $this;
    }

    /**
     * Defines as having exactly one or infinite of the next item
     *
     * @return Regex
     */
    public function oneOrMore()
    {
        $this->multiple_string = self::ONE_OR_MORE;
        return $this;
    }

    /**
     * Defines having exactly zero or infinite of the next item
     *
     * @return Regex
     */
    public function zeroOrMore()
    {
        $this->multiple_string = self::ZERO_OR_MORE;

        return $this;
    }

    /**
     * Defines having exactly zero or one of the next item
     *
     * @return Regex
     */
    public function optional()
    {
        $this->multiple_string = self::ZERO_OR_ONE;

        return $this;
    }

    /**
     * Defines the start of a line
     *
     * @return Regex
     */
    public function start()
    {
        $this->expression .= '^';

        return $this;
    }

    /**
     * Defines the end of a line
     *
     * @return Regex
     */
    public function end()
    {
        return $this->expression .= '$';
    }

    /**
     * Any lowercase characters
     *
     * @param  string $name
     * @return Regex
     */
    public function lowercase($name = null)
    {
        return $this->add("[a-z]", $name);
    }

    /**
     * Any uppercase characters
     *
     * @param  string $name
     * @return Regex
     */
    public function uppercase($name = null)
    {
        $this->add("[A-Z]", $name);

        return $this;
    }

    /**
     * Any letter of any case
     *
     * @param  string $name
     * @return Regex
     */
    public function alpha($name = null)
    {
        return $this->add("[a-zA-Z]", $name);
    }

    /**
     * Any letter, number, underscore or hyphen
     *
     * @param  string $name
     * @return Regex
     */
    public function slugchar($name = null)
    {
        $this->add("[a-zA-Z0-9-_\/]", $name);

        return $this;
    }

    /**
     * Any number of any length
     *
     * @param null $name
     * @return Regex
     */
    public function number($name = null)
    {
        $this->add("[0-9]+", $name);

        return $this;
    }


    /**
     * Match any single digit
     *
     * @param null $name
     * @return Regex
     */
    public function digit($name = null)
    {
        $this->add("[0-9]", $name);

        return $this;
    }

    /**
     * Match any alphanumeric character
     *
     * @param  string $name
     * @return Regex
     */
    public function alphanumeric($name = null)
    {
        $this->add("[a-zA-Z0-9]", $name);

        return $this;
    }

    /**
     * Baically an alias for "add"
     *
     * @param  string $string
     * @param  string $name
     * @return Regex
     */
    public function then($string, $name = null)
    {
        $this->add(preg_quote($string, self::DELIMITER), $name);

        return $this;
    }

    /**
     * Same as then, but for raw regex expressions
     *
     * @param  $string string
     * @param  string  $name
     * @return Regex
     */
    public function raw($string, $name = null)
    {
        $this->add($string, $name);

        return $this;
    }

    /**
     * A Wrapper making something optional then doing an add
     *
     * @param  string $string
     * @param  string $name
     * @return Regex
     */
    public function maybe($string, $name = null)
    {
        $this->optional();
        $this->add(preg_quote($string, self::DELIMITER), $name);

        return $this;
    }

    /**
     * Wrapper to create an optional chunk from 2 different strings
     *
     * @param  string $string1
     * @param  string $string2
     * @param  string $name
     * @return Regex
     */
    public function either($string1, $string2, $name = null)
    {
        $this->add(preg_quote($string1, self::DELIMITER) . "|" . preg_quote($string2, self::DELIMITER), $name);

        return $this;
    }

    /**
     * Like either, but for any number of elements in an array
     *
     * @param  array $elements
     * @return Regex
     */
    public function oneOf($elements = array())
    {
        $pregged_array = array(); // lol pregged is a funny word
        foreach ($elements as $element) {
            $pregged_array[] = preg_quote($element, self::DELIMITER);
        }

        $this->add(implode("|", $pregged_array));

        return $this;
    }

    /**
     * Just get the raw regex expression
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->expression;
    }

    /**
     * Match a string against the current pattern
     *
     * @param  string $text
     * @return bool
     */
    public function isMatch($text)
    {
        switch (preg_match($this->pattern, $text)) {
            case 1:
                return true;
                break;
            case 0:
                return false;
                break;
        }
    }

    /**
     * Use the magic method for the "fun" methods
     *
     * @param  string $method
     * @param  mixed  $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        $allowedMethods = array(
            'eyUp'        => 'start',
            'thatllDo'    => 'end',
            'couldAppen'  => 'maybe',
            'oneOrTother' => 'either',
            'goOnThen'    => 'then'
        );

        // If method isn't in the array baove, throw Exception
        if (!in_array($method, $allowedMethods)) {
            throw new \Exception('Method ' . $method . ' doesn\'t exist');
        }

        // Return result of method call
        return call_user_func_array($allowedMethods[$method], $arguments);

    }
}
