<?php

/**
 * Regex expression builder
 *
 * This class uses simple & fluent English words
 * to build regular expression rule.
 *
 *
 * PHP version 5.3+
 *
 * @category    Regex
 * @package     Regex expression builder
 * @author      Tomgray15
 * @version     1.0.0
 *
 */
namespace NooNoo\FluentRegex;

class Regex
{
    const ZERO_OR_MORE = "*";
    const ONE_OR_MORE = "+";
    const ZERO_OR_ONE = "?";

    protected $expression;
    protected $multiple_value = null;
    protected $multiple_value_limit = null;

    //for the cheeky hardcoded ones
    protected $multiple_string = null;

    public function __construct()
    {
        $this->expression = '';
    }

    /**
     * The base action of adding a string/piece of regex/text etc. to the current expression
     *
     * @param $string       The actual chunk of regex/text/whatever
     * @param null $name    Optional name for this capture group
     * @return $this
     */
    public function add($string, $name = null)
    {

        $this->expression .= "(";

        if (isset($name)) {
            $this->expression .= "?P<";
            $this->expression .= $name;
            $this->expression .= ">";
        }

        $this->expression .= $string;

        //handle all the multiples stuff
        if (isset($this->multiple_value)) {
            $this->expression .= "{";
            $this->expression .= $this->multiple_value;

            if (isset($multiple_value_limit)) {
                $this->expression .= "," + $this->multiple_value_limit . "}";
            } else {
                $this->expression .= "}";
            }
            $this->multiple_value = null;
            $this->multiple_value_limit = null;
        }

        //this also needs to be based on what's sent in - raw characters want it on the outside, ranges want it on the inside
        switch ($this->multiple_string) {
            case '':
                $this->expression .= $this->multiple_string;
                $this->expression .= ")";
                break;
            case self::ZERO_OR_ONE :
                $this->expression .= ")";
                $this->expression .= $this->multiple_string;
                break;
            case self::ONE_OR_MORE :
            case self::ZERO_OR_MORE :
                $this->expression .= $this->multiple_string;
                $this->expression .= ")";
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
     * @param type $count_low the minimum number of times the next chunk can appear
     * @param type $count_high the maximum number of times the next chunk can appear
     * @return type
     */
    public function between($count_low, $count_high)
    {
        $this->multiple_value = $count_low;
        $this->multiple_value_limit = $count_high;

        return $this;
    }

    /**
     * Defines as having exactly one or infinite of the next item
     */
    public function oneOrMore()
    {
        $this->multiple_string = self::ONE_OR_MORE;

        return $this;
    }

    /**
     * Defines having exactly zero or infinite of the next item
     * @return type
     */
    public function zeroOrMore()
    {
        $this->multiple_string = self::ZERO_OR_MORE;

        return $this;
    }

    /**
     * Defines having exactly zero or one of the next item
     */
    public function optional()
    {
        $this->multiple_string = self::ZERO_OR_ONE;

        return $this;
    }

    /**
     * Defines the start of a line
     * @return Regex
     */
    public function start()
    {
        $this->expression .= '^';

        return $this;
    }

    /**
     * Defines the end of a line
     * @return Regex
     */
    public function end()
    {
        return $this->expression .= '$';
    }

    /**
     * Any lowercase character
     * @return Regex
     */
    public function lowercase($name = null)
    {
        return $this->add("[a-z]", $name);
    }

    /**
     * Any uppercase character
     * @return Regex
     */
    public function uppercase($name = null)
    {
        $this->add("[A-Z]", $name);

        return $this;
    }

    /**
     * Any letter of any case
     * @param null $name
     * @return $this
     */
    public function alpha($name = null)
    {
        return $this->add("[a-zA-Z]", $name);
    }

    /**
     * Any letter, number, underscore or hyphen
     * @param null $name
     * @return $this
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
     * @return $this
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
     * @return $this
     */
    public function digit($name = null)
    {
        $this->add("[0-9]", $name);

        return $this;
    }

    /**
     * Match any alpha numeric character ()
     *
     * @param null $name
     * @return $this
     */
    public function alphanumeric($name = null)
    {
        $this->add("[a-zA-Z0-9]", $name);

        return $this;
    }

    /**
     * Baically an alias for "add"
     *
     * @param $string
     * @param null $name
     * @return $this
     */
    public function then($string, $name = null)
    {
        $this->add(preg_quote($string, "/"), $name);

        return $this;
    }

    /**
     * Same as then, but for raw regex expressions
     *
     * @param $string
     * @param null $name
     * @return $this
     */
    public function raw($string, $name = null)
    {
        $this->add($string, $name);

        return $this;
    }

    /**
     * A Wrapper making something optional then doing an add
     *
     * @param $string
     * @param null $name
     * @return $this
     */
    public function maybe($string, $name = null)
    {
        $this->optional();
        $this->add(preg_quote($string, "/"), $name);

        return $this;
    }

    /**
     * Wrapper to create an optional chunk from 2 different strings
     * @param $string1
     * @param $string2
     * @param null $name
     * @return $this
     */
    public function either($string1, $string2, $name = null)
    {
        $this->add(preg_quote($string1, "/") . "|" . preg_quote($string2, "/"), $name);

        return $this;
    }

    /**
     * Like either, but for any number of elements in an array
     * @param array $elements
     * @return $this
     */
    public function oneOf($elements = array())
    {
        $pregged_array = array(); // lol pregged is a funny word
        foreach ($elements as $element) {
            $pregged_array[] = preg_quote($element, "/");
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
        return (string)$this->expression;
    }

    /**
     * @return string
     */
    public function get()
    {
        return (string)$this->expression;
    }

    /**
     * just a quick match against some text against the current pattern
     *
     * @param $text
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
     * Yorkshire from here
     *
     * @return $this
     */
    public function eyUp()
    {
        $this->start();

        return $this;
    }

    /**
     * @return $this
     */
    public function thatllDo()
    {
        $this->end();

        return $this;
    }

    /**
     * @param $string
     * @param null $name
     * @return $this
     */
    public function couldAppen($string, $name = null)
    {
        $this->maybe($string, $name);

        return $this;
    }

    /**
     * @param $string1
     * @param $string2
     * @param null $name
     * @return $this
     */
    public function oneOrTother($string1, $string2, $name = null)
    {
        $this->either($string1, $string2, $name);

        return $this;
    }

    /**
     * @param $string
     * @param null $name
     * @return $this
     */
    public function goOnThen($string, $name = null)
    {
        $this->then($string, $name);

        return $this;
    }
}

/**
 * Class Match
 * @package NooNoo\FluentRegex
 */
class Match
{
    protected $input = '';
    protected $pattern = '';
    protected $groups = array();

    public function __construct($input = '', $pattern = '')
    {
        $this->input = $input;
        $this->pattern = $pattern;

        preg_match("/" . $pattern . "/", $input, $output_array);

        $this->groups = $output_array;
    }

    /**
     * @return $this
     */
    public function doMatch()
    {
        preg_match("/" . $pattern . "/", $input, $output_array);

        return $this;
    }

    /**
     * @param $input
     * @return $this
     */
    public function setInput($input)
    {
        $this->input = $input;

        return $this;
    }

    /**
     * @param $input
     * @return string
     */
    public function getInput($input)
    {
        return $this->input;
    }

    /**
     * @param $value
     * @return $this
     */
    public function setPattern($value)
    {
        $this->pattern = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * @return array
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @param $index_or_name
     * @return mixed
     * @throws OutOfRangeException
     */
    public function getGroup($index_or_name)
    {
        if (isset($this->groups[$index_or_name])) {
            return $this->groups[$index_or_name];
        } else {
            throw new OutOfRangeException('Attempting to find group by index or name - Group does not exist');
        }
    }

    /**
     * @return bool
     */
    public function isMatch()
    {
        if (empty($this->groups)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @return bool
     */
    public function __invoke()
    {
        if ($this->isMatch()) {
            return true;
        } else {
            return false;
        }
    }
}

/**
 * Class Replacer
 * @package NooNoo\FluentRegex
 */
class Replacer
{
    protected $pattern = '';
    protected $replacement = '';

    public function __construct($pattern = '', $replacement = '')
    {
        $this->pattern = $pattern;
        $this->replacement = $replacement;
    }

    public function getPattern()
    {
        return $this->pattern;
    }

    public function setPattern($value)
    {
        $this->pattern = $value;
    }

    public function getReplacement()
    {
        return $this->replacement;
    }

    public function setReplacement($value)
    {
        $this->replacement = $value;
    }

    /**
     * performs a generic replace on an input, using the pattern and replacement text for this replacer instance
     * or, can accept optional replacement and temp patterns to use for a single replace
     * @param  string|array $input        the input string to have some characters replaced, or an array etc.
     * @param  string $replacement      the text to replace the pattern with
     * @param  string $temp_pattern      the pattern to be replaced
     * @return string|array               the string or array, with its pattern replaced
     */
    public function replace($input, $replacement = null, $temp_pattern = null)
    {
        $replacement_to_use = '';
        $pattern_to_use = '';

        if (isset($temp_pattern)) {
            $pattern_to_use = $temp_pattern;
        } else {
            $pattern_to_use = $this->pattern;
        }

        if (isset($replacement)) {
            $replacement_to_use = $replacement;
        } else {
            $replacement_to_use = $this->replacement;
        }

        $pattern_to_use = ltrim($pattern_to_use, "^");
        $pattern_to_use = rtrim($pattern_to_use, "$");

        $input = preg_replace('/' . $pattern_to_use . '/', $replacement_to_use, $input);

        return $input;
    }
}
