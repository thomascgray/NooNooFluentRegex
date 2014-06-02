<?php

namespace NooNoo;

/**
 * Regular expression matcher
 *
 * This class matches a Regex object against another string
 *
 * @package     NooNoo
 * @author      tomgray15
 * @version     0.1.1
 *
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
