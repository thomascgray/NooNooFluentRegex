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
