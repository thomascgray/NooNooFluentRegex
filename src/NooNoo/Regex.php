<?php

namespace NooNoo;

/**
 * Regular expression builder
 *
 * This class uses simple & fluent English words to build regular expression
 * rules
 *
 * @package     NooNoo
 * @author      thomascgray
 * @version     0.1.1
 *
 */
class Regex
{
    /**
     * Class constants. You can/should override these in a subclass.
     */
    const DELIMITER                 = '#';
    const ZERO_OR_MORE              = '*';
    const ONE_OR_MORE               = '+';
    const ZERO_OR_ONE               = '?';

    /**
     * The current regex string
     *
     * @var string
     */
    protected $expression;

    /**
     * Number of times a group is repeated OR beginning of range
     *
     * @var boolean
     */
    protected $multiple_value = null;

    /**
     * Number of times a group is repeated OR beginning of range
     *
     * @var string
     */
    protected $multiple_value_limit = null;

    /**
     * Any modifiers
     *
     * @var string
     */
    protected $multiple_string = null;

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
     * 
     * @return Regex
     */
    public function add($string, $name = null)
    {
        $this->expression .= '(';

        if (isset($name)) {
            $this->expression .= '?P<' . $name . '>';
        }

        $this->expression .= $string;

        $this->addLimit();
        $this->addModifier();

        return $this;
    }

    /**
     * Defines the start of a line
     *
     * @return Regex
     */
    public function start()
    {
        $this->expression .= "^";

        return $this;
    }

    /**
     * Defines the end of a line
     *
     * @return Regex
     */
    public function end()
    {
        return $this->expression .= "$";
    }

    /**
     * Any lowercase characters
     *
     * @param  string $name
     * 
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
     * 
     * @return Regex
     */
    public function uppercase($name = null)
    {
        return $this->add("[A-Z]", $name);
    }

    /**
     * Any letter of any case
     *
     * @param  string $name
     * 
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
     * 
     * @return Regex
     */
    public function slugchar($name = null)
    {
        return $this->add("[a-zA-Z0-9-_\/]", $name);
    }

    /**
     * Any number of any length
     *
     * @param  string $name
     * 
     * @return Regex
     */
    public function number($name = null)
    {
        return $this->add("[0-9]+", $name);
    }


    /**
     * Match a single digit
     *
     * @param  string $name
     * 
     * @return Regex
     */
    public function digit($name = null)
    {
        return $this->add("[0-9]", $name);
    }

    /**
     * Match any alphanumeric character
     *
     * @param  string $name
     * 
     * @return Regex
     */
    public function alphanumeric($name = null)
    {
        return $this->add("[a-zA-Z0-9]", $name);
    }

    /**
     * An alias for "add"
     *
     * @param  string $string
     * @param  string $name
     * @return Regex
     */
    public function then($string, $name = null)
    {
        return $this->add(preg_quote($string, self::DELIMITER), $name);
    }

    /**
     * Same as `add()`, but for raw regex expressions
     *
     * @param  string  $string
     * @param  string  $name
     * 
     * @return Regex
     */
    public function raw($string, $name = null)
    {
        return $this->add($string, $name);
    }

    /**
     * Make something optional then do an add
     *
     * @param  string $string
     * @param  string $name
     * 
     * @return Regex
     */
    public function maybe($string, $name = null)
    {
        $this->optional();
        return $this->add(preg_quote($string, self::DELIMITER), $name);
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
        return $this->add(
            preg_quote($string1, self::DELIMITER) . "|" . preg_quote($string2, self::DELIMITER),
            $name
        );
    }

    /**
     * Like either, but for any number of elements in an array
     *
     * @param  array $elements
     * @return Regex
     */
    public function oneOf($elements = array())
    {
        // Get the first element from the array
        $expression = array_shift($elements);

        foreach ($elements as $element) {
            $expression .= '|' . preg_quote($element, self::DELIMITER);
        }

        return $this->add($expression);
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
     * Get the raw regular expression
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
        @$match = preg_match($this->expression, $text);

        if ($match === false) {
            throw new \Exception('The regular expression is invalid');
        }

        return preg_match($this->expression, $text) === 1
            ? true
            : false;
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
            "eyUp"        => "start",
            "thatllDo"    => "end",
            "couldAppen"  => "maybe",
            "oneOrTother" => "either",
            "goOnThen"    => "then"
        );

        // If method isn"t in the array above, throw Exception
        if (!in_array($method, array_keys($allowedMethods))) {
            throw new \Exception("Method " . $method . " doesn\"t exist");
        }

        // Return result of method call
        return call_user_func_array(
            array($this, $allowedMethods[$method]),
            $arguments
        );
    }

    protected function addLimit()
    {
        if (isset($this->multiple_value)) {
            $this->expression .= '{' . $this->multiple_value;

            $this->expression .= isset($this->multiple_value_limit)
                ? ',' . $this->multiple_value_limit . '}'
                : '}';

            $this->multiple_value       = null;
            $this->multiple_value_limit = null;
        }
    }

    protected function addModifier()
    {
        // Handle modifiers
        $this->expression .= $this->multiple_string === self::ZERO_OR_ONE
            ? ')' . $this->multiple_string
            : $this->multiple_string . ')';

        $this->multiple_string = '';
    }
}
