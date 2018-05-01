<?php
namespace NooNoo;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2014-06-02 at 11:32:12.
 */
class RegexTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Regex
     */
    protected $regex;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->regex = new Regex;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers NooNoo\Regex::__construct()
     * @covers NooNoo\Regex::__toString
     */
    public function testConstruct()
    {
        $actual = new Regex;
        $this->assertEmpty((string) $actual);
    }

    /**
     * @covers NooNoo\Regex::add
     */
    public function testAdd()
    {
        $actual = $this->regex->add('add');
        $this->assertEquals('(add)', $actual);
    }

    /**
     * @covers NooNoo\Regex::add
     */
    public function testAddWithNamedGroup()
    {
        $actual = $this->regex->add('add', 'name');
        $this->assertEquals('(?P<name>add)', $actual);
    }

    /**
     * @covers NooNoo\Regex::add
     * @covers NooNoo\Regex::addLimit
     */
    public function testAddWithLimit()
    {
        $actual = $this->regex->between(1, 2)->add('add');
        $this->assertEquals('(add{1,2})', $actual);
    }

    /**
     * @covers NooNoo\Regex::add
     * @covers NooNoo\Regex::addModifier
     */
    public function testAddWithModifierZeroOrMore()
    {
        $actual = $this->regex->zeroOrMore()->add('zeroOrMore');
        $this->assertEquals('(zeroOrMore*)', $actual);
    }

    /**
     * @covers NooNoo\Regex::add
     * @covers NooNoo\Regex::addModifier
     * @covers NooNoo\Regex::optional
     */
    public function testAddWithModifierOptional()
    {
        $actual = $this->regex->optional()->add('zeroOrOne');
        $this->assertEquals('(zeroOrOne)?', $actual);
    }


    /**
     * @covers NooNoo\Regex::start
     */
    public function testStart()
    {
        $actual = $this->regex->start();
        $this->assertEquals('^', $actual);
    }

    /**
     * @covers NooNoo\Regex::end
     */
    public function testEnd()
    {
        $actual = $this->regex->end();
        $this->assertEquals('$', $actual);
    }

    /**
     * @covers NooNoo\Regex::lowercase
     */
    public function testLowercase()
    {
        $actual = $this->regex->lowercase();
        $this->assertEquals('([a-z])', $actual);
    }

    /**
     * @covers NooNoo\Regex::uppercase
     */
    public function testUppercase()
    {
        $actual = $this->regex->uppercase();
        $this->assertEquals('([A-Z])', $actual);
    }

    /**
     * @covers NooNoo\Regex::alpha
     */
    public function testAlpha()
    {
        $actual = $this->regex->alpha();
        $this->assertEquals('([a-zA-Z])', $actual);
    }

    /**
     * @covers NooNoo\Regex::slugchar
     */
    public function testSlugchar()
    {
        $actual = $this->regex->slugchar();
        $this->assertEquals('([a-zA-Z0-9-_\/])', $actual);
    }

    /**
     * @covers NooNoo\Regex::number
     */
    public function testNumber()
    {
        $actual = $this->regex->number();
        $this->assertEquals('([0-9]+)', $actual);
    }

    /**
     * @covers NooNoo\Regex::digit
     */
    public function testDigit()
    {
        $actual = $this->regex->digit();
        $this->assertEquals('([0-9])', $actual);
    }

    /**
     * @covers NooNoo\Regex::alphanumeric
     */
    public function testAlphanumeric()
    {
        $actual = $this->regex->alphanumeric();
        $this->assertEquals('([a-zA-Z0-9])', $actual);

        // So we can use the @depends annotation
        return $actual;
    }

    /**
     * @covers  NooNoo\Regex::then
     * @depends testAlphanumeric
     */
    public function testThen($regex)
    {
        $actual = $regex->then('teststring');
        $this->assertEquals('([a-zA-Z0-9])(teststring)', $actual);
    }

    /**
     * @covers  NooNoo\Regex::raw
     * @depends testAlphanumeric
     */
    public function testRaw($regex)
    {
        $actual = $regex->raw('(.*)');
        $this->assertEquals('([a-zA-Z0-9])(teststring)((.*))', $actual);
    }

    /**
     * @covers  NooNoo\Regex::maybe
     * @depends testAlphanumeric
     */
    public function testMaybe($regex)
    {
        $actual = $regex->maybe('perhaps');
        $this->assertEquals('([a-zA-Z0-9])(teststring)((.*))(perhaps)?', $actual);
    }

    /**
     * @covers  NooNoo\Regex::either
     * @depends testAlphanumeric
     */
    public function testEither($regex)
    {
        $actual = $regex->either('couldbe', 'mightbe');
        $this->assertEquals(
            '([a-zA-Z0-9])(teststring)((.*))(perhaps)?(couldbe|mightbe)',
            $actual
        );
    }

    /**
     * @covers  NooNoo\Regex::oneOf
     * @depends testAlphanumeric
     * @todo   Implement testOneOf().
     */
    public function testOneOf($regex)
    {
        $actual = $regex->oneOf(array('is', 'it', 'in'));
        $this->assertEquals(
            '([a-zA-Z0-9])(teststring)((.*))(perhaps)?(couldbe|mightbe)(is|it|in)',
            $actual
        );
    }

    /**
     * @covers NooNoo\Regex::multiple
     */
    public function testMultiple()
    {
        $actual = $this->regex->multiple(2)->uppercase();
        $this->assertEquals('([A-Z]{2})', $actual);
    }

    /**
     * @covers NooNoo\Regex::between
     */
    public function testBetween()
    {
        $actual = $this->regex->between(1, 2)->uppercase();
        $this->assertEquals('([A-Z]{1,2})', $actual);
    }

    /**
     * @covers  NooNoo\Regex::oneOrMore
     */
    public function testOneOrMore()
    {
        $actual = $this->regex->oneOrMore()->uppercase();
        $this->assertEquals('([A-Z]+)', $actual);
    }

    /**
     * @covers NooNoo\Regex::zeroOrMore
     */
    public function testZeroOrMore()
    {
        $actual = $this->regex->zeroOrMore()->uppercase();
        $this->assertEquals('([A-Z]*)', $actual);
    }

    /**
     * @covers NooNoo\Regex::optional
     * @todo   Implement testOptional().
     */
    public function testOptional()
    {
        $actual = $this->regex->optional()->uppercase();
        $this->assertEquals('([A-Z])?', $actual);
    }

    /**
     * @covers NooNoo\Regex::get
     * @todo   Implement testGet().
     */
    public function testGet()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers NooNoo\Regex::isMatch
     */
    public function testIsMatch()
    {
        $actual = $this->regex->uppercase()->isMatch('Bs');
        $this->assertTrue($actual);
    }

    /**
     * @covers NooNoo\Regex::isMatch
     */
    public function testIsNotMatch()
    {
        $actual = $this->regex->uppercase()->isMatch('ss');
        $this->assertFalse($actual);
    }

    /**
     * @covers NooNoo\Regex::isMatch
     * @expectedException Exception
     */
    public function testIsMatchFailure()
    {
        $actual = $this->regex->raw('+++')->isMatch('aaa');
    }

    /**
     * Extra methods
     */

    /**
     * @covers NooNoo\Regex::__call
     */
    public function testYorkshireMethods()
    {
        $actual = $this->regex->eyUp()
            ->goOnThen('test')
            ->couldAppen('s')
            ->goOnThen('more')
            ->couldAppen('text')
            ->oneOrTother('maybe', 'maybenot')
            ->thatllDo();

        $this->assertEquals('^(test)(s)?(more)(text)?(maybe|maybenot)$', $actual);
    }

    /**
     * @covers            NooNoo\Regex::__call()
     * @expectedException Exception
     */
    public function testNonexistentMethod()
    {
        $this->regex->trololol();
    }
}