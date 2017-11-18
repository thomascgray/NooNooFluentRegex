<?php

namespace NooNoo;

/**
 * Regular expression matcher
 *
 * This class matches a Regex object against another string
 *
 * @package     NooNoo
 * @author      thomascgray
 * @version     0.1.1
 *
 */
class Match
{
    /**
     * the input of this Match instance to match the regex against
     *
     * @var string
     */
    protected $input = '';

    /**
     * the regex pattern that we will be matching against the input
     *
     * @var string
     */
    protected $pattern = '';

    /**
     * any capture groups that were found from matching the pattern regex against the input
     *
     * @var string[]
     */
    protected $groups = array();

    /**
     * takes an input
     *
     * @param string $input
     * @param string $pattern
     */
    public function __construct($input = '', $pattern = '')
    {
        $this->input = $input;
        $this->pattern = $pattern;

        preg_match("/" . $pattern . "/", $input, $output_array);

        $this->groups = $output_array;
    }

    /**
     * set the input
     *
     * @param string $input
     * @return $this
     */
    public function setInput($input)
    {
        $this->input = $input;

        return $this;
    }

    /**
     * gets the input
     *
     * @return string
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * sets the regex pattern
     * 
     * @param string $value
     * @return $this
     */
    public function setPattern($value)
    {
        $this->pattern = $value;

        return $this;
    }

    /**
     * gets the regex pattern
     * 
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * returns the capture groups
     * 
     * @return array
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * gets a specific group by its index in the main groups array
     * 
     * @param string|integer $index
     * @return string
     * @throws OutOfRangeException
     */
    public function getGroup($index)
    {
        if (isset($this->groups[$index])) {
            return $this->groups[$index];
        } else {
            throw new OutOfRangeException('Attempting to find group by index or name - Group does not exist');
        }
    }

    /**
     * returns true if this Match __construct() found any capture groups - otherwise false
     * 
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
     * calls $this->isMatch() when invoked
     * 
     * @return bool
     */
    public function __invoke()
    {
        return $this->isMatch();
    }
}
