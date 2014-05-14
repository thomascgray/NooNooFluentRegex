<?php

/*
TODO

add each name to an array and check against them so we dont allow duplicate names
*/
class Regex
{
	//the actual expression we're going to return at the end
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
	 * @param string $the actual chunk of regex/text/whatever 
	 * @param string $optional name for this capture group
	 * @return type
	 */
	public function add($string, $name = null)
	{
		$this->expression .= "(";
		
		if ($name != null)
		{
			$this->expression .= "?P<".$name.">";
		}
		
		if (isset($multiple_string))
		{
			$this->expression .= $this->multiple_string;
			$this->multiple_string = null;
		}
		else
		{
			//handle all the multiples stuff		
			if (isset($multiple_value))
			{
				$this->expression .= "{" + $this->multiple_value;
			}
			
			if (isset($multiple_value_limit))
			{
				$this->expression .= "," + $this->multiple_value . "}";
			}
			else
			{
				$this->expression .= "}";
			}		
			$this->multiple_value = null;
			$this->multiple_value_limit = null;
		}
		
		$this->expression .= ")";		

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
		$this->multiple_string = "+";
		
		return $this;
	}
	
	/**
	 * Defines having exactly zero or infinite of the next item
	 * @return type
	 */
	public function zeroOrMore()
	{
		$this->multiple_string = "*";
		
		return $this;	
	}
	
	/**
	 * Defines having exactly zero or one of the next item
	 */
	public function zeroOrOne()
	{
		$this->multiple_string = "?";		
		
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
		$this->expression .= '$';
		
		return $this;
	}
	
	/**
	 * Any lowercase character
	 * @return Regex
	 */
	public function lowercase()
	{
		$this->add("[a-z]");
		
		return $this;
	}
	
	/**
	 * Any uppercase character
	 * @return Regex
	 */
	public function uppercase()
	{
		$this->add("[A-Z]");
		
		return $this;
	}
	
	/**
	 * Any letter of any case
	 * @return Regex
	 */
	public function alpha()
	{
		$this->add("[a-zA-Z]");
		
		return $this;
	}
	
	/**
	 * Any letter, number, underscore or hyphen
	 * @return Regex
	 */
	public function slugchar()
	{
		$this->add("[a-zA-Z0-9-_]");
		
		return $this;
	}
	
	/**
	 * Any number
	 * @return Regex
	 */
	public function number()
	{
		$this->add("[0-9]");
		
		return $this;
	}
	
	/**
	 * Any alpha numeric character (letter of any case or number)
	 * @return Regex
	 */
	public function alphanumeric()
	{
		$this->add("[a-zA-Z0-9]");
		
		return $this;
	}
}