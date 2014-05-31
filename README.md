NooNooFluentRegex
=================

Build Regex expressions using fluent setters and English language terms

```php
$regex = new Regex();

//regex for a url
$regex->start()
		->then("http")
		->maybe("s")
		->then("://")
		->maybe("www.")
		->oneOrMore()
		->slugchar()
		->either(".co", ".com")
		->zeroOrMore()
		->slugchar()
		->end();
		
echo $regex;

//^(http)(s)?(\:\/\/)(www\.)?([a-zA-Z0-9-_\/]+)(\.co|\.com)([a-zA-Z0-9-_\/]*)$
```
####TODO
- Add docs for every kind of "chunk" you can use.
- Make some "flag" chunks i.e everything between these 2 is optional in and of itself.
- Get it PSR complient
- Get it on composer

####Exclusive! Build your Regex using _Yorkshire Slang!_

Here's the above example, but this time, a bit more..._~~Northern~~ Better_

```php
$regex->eyUp()
		->goOnThen("http")
		->couldAppen("s")
		->goOnThen("://")
		->couldAppen("www.")
		->oneOrMore()
		->slugchar()
		->oneOrTother(".co", ".com")
		->zeroOrMore()
		->slugchar()
		->thatllDo();
```

###Finding Matches

We can use the Match class to return capture groups for an input and a Regex pattern (as well as checking that the pattern matches the text, obviously)

__Simple Match__

```php

$regex = new Regex();

$regex->start()
		->then("Hello, ")
		->then("world!")
		->end();

$match = new Match("Hello, world!", $regex);

$match->isMatch(); // returns true

//thanks to invoke, you can also just check against the object itself

//ex. this returns true
if ($match)
	return true;
else
	return false;
```

If you build your pattern using a NooNooFluentRegex Regex object, each "chunk" that could contain characters will be its own capture group.

```php
//using the Regex object from the URL example

$match = new Match("http://www.example.com/example_page", $regex);

echo $match->getGroup(5); // echos "example"
```
You can also return groups using specified names

```php
$regex = new Regex();

$regex->start()
	->lowercase("lowercase")
	->uppercase("uppercase")
	->raw("[A-Z][a-z]+", "name")
	->digit("number1")
	->digit("number2") // number 2...hehehehe
	->end();

$match = new Match("aZTom69", $regex);

echo $match->getGroup("name"); // echos "Tom"
echo $match->getGroup("uppercase"); // echos "Z"

```
###Doing Replacements

We can use the Replacer class to do both mass replacements and individual, case-by-case replacements.

```php

$regex = new Regex();

$regex->start()
		->raw("[A-Z][a-z]+")
		->end();

//we set up the replacer with a pattern, and a replacement to use
$replacer = new Replacer($regex, "Tom");

//now that those 2 are set up, you can start doing mass replacements

echo $replacer->replace("Sam went to play football"); //echos out "Tom went to play football";
echo $replacer->replace("Dave goes to the shop"); //echos out "Tom goes to the shop";
```

As said, you can also pass in optional arguments to the replace function to do case by case replacements.

```php
//using the same variables as before

echo $replacer->replace("Martin plays too much Starcraft", "Sally"); //echos out "Sally plays too much Starcraft";
```

You can also pass in an optional third argument of a new pattern to use for a case-by-case replacement.
