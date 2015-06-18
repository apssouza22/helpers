<?php

namespace Helpers;

/**
 * Handle money's operations
 *
 * @author Apssouza
 */
class Money {

    private $amount;

    public function getAmount() {
        return $this->amount;
    }

    public function getValue() {
        return round($this->amount / 100, 2, PHP_ROUND_HALF_UP);
    }

    public function __construct($amount) {
        if (!is_int($amount)) {
            throw new \InvalidArgumentException('$amount must be an integer');
        }
        $this->amount = $amount;
    }

    public function getBrFormat() {
        $value = $this->getValue();
        return number_format($value, 2, ',', '.');
    }

    public static function fromString($value) {
        $value = self::handleValueArgument($value);
        return new static(
                intval(
                        round(
                                100 *
                                round(
                                        $value, 2, PHP_ROUND_HALF_UP
                                ), 0, PHP_ROUND_HALF_UP
                        )
                )
        );
    }

    private function newMoney($value) {
        if (is_int($value)) {
            return new static($value);
        } else {
            return self::fromString($value);
        }
    }

    public function multiply($factor, $roundingMode = PHP_ROUND_HALF_UP) {
        return $this->newMoney(
                        intval(
                                round($factor * $this->amount, 0, $roundingMode)
                        )
        );
    }

    public function divide($factor, $roundingMode = PHP_ROUND_HALF_UP) {
        return $this->newMoney(
                        intval(
                                round($this->amount / $factor, 0, $roundingMode)
                        )
        );
    }

    public function subtract($value) {
        $other = self::fromString($value);
        $value = $this->amount - $other->getAmount();
        return $this->newMoney($value);
    }

    public function add($value) {
        $other = self::fromString($value);
        $value = $this->amount + $other->getAmount();
        return $this->newMoney($value);
    }

    /**
     * Extracts a percentage of the monetary value represented by this Money
     * object and returns an array of two Money objects:
     * $original = $result['subtotal'] + $result['percentage'];
     *
     * Please note that this extracts the percentage out of a monetary value
     * where the percentage is already included. If you want to get the
     * percentage of the monetary value you should use multiplication
     * (multiply(0.21), for instance, to calculate 21% of a monetary value
     * represented by a Money object) instead.
     *
     * @param  float $percentage
     * @param  integer $roundingMode
     * @return static[]
     * @see    https://github.com/sebastianbergmann/money/issues/27
     */
    public function extractPercentage($percentage, $roundingMode = PHP_ROUND_HALF_UP) {
        $percentage = $this->multiply($percentage / 100);
        return array(
            'percentage' => $percentage,
            'subtotal' => $this->subtract($percentage->getValue())
        );
    }

    public function compareTo(Money $other) {
        if ($this->amount == $other->getAmount()) {
            return 0;
        }
        return $this->amount < $other->getAmount() ? -1 : 1;
    }

    /**
     * Returns TRUE if this Money object equals to another.
     *
     * @param  \Helpers\Money $other
     * @return boolean
     * @throws \Helpers\CurrencyMismatchException
     */
    public function equals(Money $other) {
        return $this->compareTo($other) == 0;
    }

    /**
     * Returns TRUE if the monetary value represented by this Money object
     * is greater than that of another, FALSE otherwise.
     *
     * @param  \Helpers\Money $other
     * @return boolean
     */
    public function greaterThan(Money $other) {
        return $this->compareTo($other) == 1;
    }

    /**
     * Returns TRUE if the monetary value represented by this Money object
     * is greater than or equal that of another, FALSE otherwise.
     *
     * @param  \Helpers\Money $other
     * @return boolean
     */
    public function greaterThanOrEqual(Money $other) {
        return $this->greaterThan($other) || $this->equals($other);
    }

    /**
     * Returns TRUE if the monetary value represented by this Money object
     * is smaller than that of another, FALSE otherwise.
     *
     * @param  \Helpers\Money $other
     * @return boolean
     */
    public function lessThan(Money $other) {
        return $this->compareTo($other) == -1;
    }

    /**
     * Returns TRUE if the monetary value represented by this Money object
     * is smaller than or equal that of another, FALSE otherwise.
     *
     * @param  \Helpers\Money $other
     * @return boolean
     */
    public function lessThanOrEqual(Money $other) {
        return $this->lessThan($other) || $this->equals($other);
    }

    public static function handleValueArgument($value) {
        if (strrpos($value, ',') > strrpos($value, '.')) {
            return str_replace(
                    ',', '.', str_replace('.', '', $value)
            );
        }
        return str_replace(',', '', $value);
    }

    /**
     * Returns a new Money object that represents the negated monetary value
     * of this Money object.
     *
     * @return static
     */
    public function negate() {
        return $this->newMoney(-1 * $this->amount);
    }

}

//$porc = Money::fromString('1.022,00')->multiply(0.10);
//var_dump($porc);
//var_dump($porc->getBrFormat());
//
//$porc = Money::fromString('1.000,00')->extractPercentage(10);
//var_dump($porc['percentage']->getBrFormat());
//var_dump($porc['subtotal']->getBrFormat());