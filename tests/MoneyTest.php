<?php

namespace Helpers;

require_once dirname(__FILE__) . '/../Loader.php';
$loader = new \Helpers\Loader(array('../'));
$loader->register();

//require_once dirname(__FILE__) . '/../Money.php';

class MoneyTest extends \PHPUnit_Framework_TestCase {

    /**
     * @covers            \Helpers\Money::__construct
     * @expectedException InvalidArgumentException
     */
    public function testCannotBeConstructedFromNonIntegerValue() {
            new Money(null);
        
    }


    /**
     * @covers \Helpers\Money::fromString
     * @uses   \Helpers\Money::__construct
     * @uses   \Helpers\Money::handleCurrencyArgument
     */
    public function testObjectCanBeConstructedFromStringValueAndCurrencyObject() {
        $this->assertEquals(
                new Money(1234), Money::fromString('12.34')
        );
    }

    /**
     * @covers \Helpers\Money::fromString
     * @uses   \Helpers\Money::__construct
     * @uses   \Helpers\Money::handleCurrencyArgument
     */
    public function testObjectCanBeConstructedFromStringValueAndCurrencyString() {
        $this->assertEquals(
                new Money(1234), Money::fromString('12.34', 'EUR')
        );
    }

    /**
     * @covers  \Helpers\Money::getAmount
     * @depends testObjectCanBeConstructedFromIntegerValueAndCurrencyObject
     * @param   Money $m
     */
    public function testAmountCanBeRetrieved(Money $m) {
        $this->assertEquals(0, $m->getAmount());
    }


    /**
     * @covers \Helpers\Money::add
     * @covers \Helpers\Money::newMoney
     * @covers \Helpers\Money::assertSameCurrency
     * @uses   \Helpers\Money::__construct
     * @uses   \Helpers\Money::handleCurrencyArgument
     * @uses   \Helpers\Money::getAmount
     * @uses   \Helpers\Money::getCurrency
     * @uses   \Helpers\Money::assertIsInteger
     */
    public function testAnotherMoneyObjectWithSameCurrencyCanBeAdded() {
        $a = new Money(1);
        $b = new Money(2);
        $c = $a->add($b);
        $this->assertEquals(1, $a->getAmount());
        $this->assertEquals(2, $b->getAmount());
        $this->assertEquals(3, $c->getAmount());
    }

    
    

    /**
     * @covers \Helpers\Money::subtract
     * @covers \Helpers\Money::newMoney
     * @covers \Helpers\Money::assertSameCurrency
     * @uses   \Helpers\Money::__construct
     * @uses   \Helpers\Money::handleCurrencyArgument
     * @uses   \Helpers\Money::getAmount
     * @uses   \Helpers\Money::getCurrency
     * @uses   \Helpers\Money::assertIsInteger
     */
    public function testAnotherMoneyObjectWithSameCurrencyCanBeSubtracted() {
        $a = new Money(1);
        $b = new Money(2);
        $c = $b->subtract($a);
        $this->assertEquals(1, $a->getAmount());
        $this->assertEquals(2, $b->getAmount());
        $this->assertEquals(1, $c->getAmount());
    }

    /**
     * @covers            \Helpers\Money::subtract
     * @covers            \Helpers\Money::newMoney
     * @covers            \Helpers\Money::assertSameCurrency
     * @covers            \Helpers\Money::assertIsInteger
     * @uses              \Helpers\Money::__construct
     * @uses              \Helpers\Money::handleCurrencyArgument
     * @uses              \Helpers\Money::getAmount
     * @expectedException \OverflowException
     */
    public function testExceptionIsThrownForOverflowingSubtraction() {
        $a = new Money(-PHP_INT_MAX);
        $b = new Money(2);
        $a->subtract($b);
    }

  

    /**
     * @covers \Helpers\Money::negate
     * @covers \Helpers\Money::newMoney
     * @uses   \Helpers\Money::__construct
     * @uses   \Helpers\Money::handleCurrencyArgument
     * @uses   \Helpers\Money::getAmount
     */
    public function testCanBeNegated() {
        $a = new Money(1);
        $b = $a->negate();
        $this->assertEquals(1, $a->getAmount());
        $this->assertEquals(-1, $b->getAmount());
    }

    /**
     * @covers \Helpers\Money::multiply
     * @covers \Helpers\Money::newMoney
     * @covers \Helpers\Money::castToInt
     * @uses   \Helpers\Money::__construct
     * @uses   \Helpers\Money::handleCurrencyArgument
     * @uses   \Helpers\Money::getAmount
     * @uses   \Helpers\Money::assertInsideIntegerBounds
     * @uses   \Helpers\Currency
     */
    public function testCanBeMultipliedByAFactor() {
        $a = new Money(1);
        $b = $a->multiply(2);
        $this->assertEquals(1, $a->getAmount());
        $this->assertEquals(2, $b->getAmount());
    }



    /**
     * @covers \Helpers\Money::extractPercentage
     * @uses   \Helpers\Money::__construct
     * @uses   \Helpers\Money::getAmount
     * @uses   \Helpers\Money::getCurrency
     * @uses   \Helpers\Money::subtract
     * @uses   \Helpers\Money::assertSameCurrency
     * @uses   \Helpers\Money::assertIsInteger
     * @uses   \Helpers\Money::assertInsideIntegerBounds
     * @uses   \Helpers\Money::castToInt
     * @uses   \Helpers\Money::newMoney
     */
    public function testPercentageCanBeExtracted() {
        $original = new Money(10000);
        $extract = $original->extractPercentage(21);
        $this->assertEquals(new Money(7900), $extract['subtotal']);
        $this->assertEquals(new Money(2100), $extract['percentage']);
    }


    /**
     * @covers \Helpers\Money::compareTo
     * @covers \Helpers\Money::assertSameCurrency
     * @uses   \Helpers\Money::__construct
     * @uses   \Helpers\Money::handleCurrencyArgument
     * @uses   \Helpers\Money::getAmount
     * @uses   \Helpers\Money::getCurrency
     * @uses   \Helpers\Currency
     */
    public function testCanBeComparedToAnotherMoneyObjectWithSameCurrency() {
        $a = new Money(1);
        $b = new Money(2);
        $this->assertEquals(-1, $a->compareTo($b));
        $this->assertEquals(1, $b->compareTo($a));
        $this->assertEquals(0, $a->compareTo($a));
    }

    /**
     * @covers  \Helpers\Money::greaterThan
     * @covers  \Helpers\Money::assertSameCurrency
     * @uses    \Helpers\Money::__construct
     * @uses    \Helpers\Money::handleCurrencyArgument
     * @uses    \Helpers\Money::compareTo
     * @uses    \Helpers\Money::getAmount
     * @uses    \Helpers\Money::getCurrency
     * @uses    \Helpers\Currency
     * @depends testCanBeComparedToAnotherMoneyObjectWithSameCurrency
     */
    public function testCanBeComparedToAnotherMoneyObjectWithSameCurrency2() {
        $a = new Money(1);
        $b = new Money(2);
        $this->assertFalse($a->greaterThan($b));
        $this->assertTrue($b->greaterThan($a));
    }

    /**
     * @covers  \Helpers\Money::lessThan
     * @covers  \Helpers\Money::assertSameCurrency
     * @uses    \Helpers\Money::__construct
     * @uses    \Helpers\Money::handleCurrencyArgument
     * @uses    \Helpers\Money::compareTo
     * @uses    \Helpers\Money::getAmount
     * @uses    \Helpers\Money::getCurrency
     * @uses    \Helpers\Currency
     * @depends testCanBeComparedToAnotherMoneyObjectWithSameCurrency
     */
    public function testCanBeComparedToAnotherMoneyObjectWithSameCurrency3() {
        $a = new Money(1);
        $b = new Money(2);
        $this->assertFalse($b->lessThan($a));
        $this->assertTrue($a->lessThan($b));
    }

    /**
     * @covers  \Helpers\Money::equals
     * @covers  \Helpers\Money::assertSameCurrency
     * @uses    \Helpers\Money::__construct
     * @uses    \Helpers\Money::handleCurrencyArgument
     * @uses    \Helpers\Money::compareTo
     * @uses    \Helpers\Money::getAmount
     * @uses    \Helpers\Money::getCurrency
     * @uses    \Helpers\Currency
     * @depends testCanBeComparedToAnotherMoneyObjectWithSameCurrency
     */
    public function testCanBeComparedToAnotherMoneyObjectWithSameCurrency4() {
        $a = new Money(1);
        $b = new Money(1);
        $this->assertEquals(0, $a->compareTo($b));
        $this->assertEquals(0, $b->compareTo($a));
        $this->assertTrue($a->equals($b));
        $this->assertTrue($b->equals($a));
    }

    /**
     * @covers  \Helpers\Money::greaterThanOrEqual
     * @covers  \Helpers\Money::assertSameCurrency
     * @uses    \Helpers\Money::__construct
     * @uses    \Helpers\Money::handleCurrencyArgument
     * @uses    \Helpers\Money::greaterThan
     * @uses    \Helpers\Money::equals
     * @uses    \Helpers\Money::compareTo
     * @uses    \Helpers\Money::getAmount
     * @uses    \Helpers\Money::getCurrency
     * @uses    \Helpers\Currency
     * @depends testCanBeComparedToAnotherMoneyObjectWithSameCurrency
     */
    public function testCanBeComparedToAnotherMoneyObjectWithSameCurrency5() {
        $a = new Money(2);
        $b = new Money(2);
        $c = new Money(1);
        $this->assertTrue($a->greaterThanOrEqual($a));
        $this->assertTrue($a->greaterThanOrEqual($b));
        $this->assertTrue($a->greaterThanOrEqual($c));
        $this->assertFalse($c->greaterThanOrEqual($a));
    }

    /**
     * @covers  \Helpers\Money::lessThanOrEqual
     * @covers  \Helpers\Money::assertSameCurrency
     * @uses    \Helpers\Money::__construct
     * @uses    \Helpers\Money::handleCurrencyArgument
     * @uses    \Helpers\Money::lessThan
     * @uses    \Helpers\Money::equals
     * @uses    \Helpers\Money::compareTo
     * @uses    \Helpers\Money::getAmount
     * @uses    \Helpers\Money::getCurrency
     * @uses    \Helpers\Currency
     * @depends testCanBeComparedToAnotherMoneyObjectWithSameCurrency
     */
    public function testCanBeComparedToAnotherMoneyObjectWithSameCurrency6() {
        $a = new Money(1);
        $b = new Money(1);
        $c = new Money(2);
        $this->assertTrue($a->lessThanOrEqual($a));
        $this->assertTrue($a->lessThanOrEqual($b));
        $this->assertTrue($a->lessThanOrEqual($c));
        $this->assertFalse($c->lessThanOrEqual($a));
    }

    /**
     * @covers            \Helpers\Money::compareTo
     * @covers            \Helpers\Money::assertSameCurrency
     * @uses              \Helpers\Money::__construct
     * @uses              \Helpers\Money::handleCurrencyArgument
     * @uses              \Helpers\Money::getCurrency
     * @uses              \Helpers\Currency
     * @expectedException \Helpers\CurrencyMismatchException
     */
    public function testExceptionIsRaisedWhenComparedToMoneyObjectWithDifferentCurrency() {
        $a = new Money(1);
        $b = new Money(2);
        $a->compareTo($b);
    }

}
