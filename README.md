NooNooFluentRegex
=================

Build Regex expressions using fluent setters and English language terms

```
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
```
