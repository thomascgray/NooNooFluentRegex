<?php

namespace NooNoo;

/**
 * Regular expression replacer
 *
 * This class replaces a string with a Regex object
 *
 * @package     NooNoo
 * @author      Tom Gray 
 * @version     0.1.1
 *
 */
class Replace
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
