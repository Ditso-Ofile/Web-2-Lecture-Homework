<?php

namespace App\Services;

use InvalidArgumentException;

/**
 * TEST MENU - Business logic of the calculator.
 * Kept separate from the controller so it can be unit tested on its own
 * (tests/Unit/CalculatorTest.php).
 */
class Calculator
{
    // Supported operations: key => symbol shown on the page
    public const OPERATIONS = [
        'add' => '+',
        'sub' => '-',
        'mul' => '*',
        'div' => '/',
        'pow' => '^',
        'mod' => 'mod',
    ];

    public function add(float $a, float $b): float
    {
        return $a + $b;
    }

    public function subtract(float $a, float $b): float
    {
        return $a - $b;
    }

    public function multiply(float $a, float $b): float
    {
        return $a * $b;
    }

    public function divide(float $a, float $b): float
    {
        if ($b == 0.0) {
            throw new InvalidArgumentException('You cannot divide by zero!');
        }

        return $a / $b;
    }

    public function power(float $a, float $b): float
    {
        if ($a == 0.0 && $b < 0) {
            throw new InvalidArgumentException('Zero cannot be raised to a negative power!');
        }

        $result = $a ** $b;

        // e.g. (-8) ^ 0.5 is not a real number
        if (is_nan($result)) {
            throw new InvalidArgumentException('The result is not a real number!');
        }
        if (is_infinite($result)) {
            throw new InvalidArgumentException('The result is too large!');
        }

        return $result;
    }

    public function modulo(float $a, float $b): float
    {
        if ($b == 0.0) {
            throw new InvalidArgumentException('You cannot divide by zero!');
        }

        return fmod($a, $b);
    }

    // Runs the operation selected by its key ('add', 'sub', ...)
    public function calculate(string $operation, float $a, float $b): float
    {
        return match ($operation) {
            'add' => $this->add($a, $b),
            'sub' => $this->subtract($a, $b),
            'mul' => $this->multiply($a, $b),
            'div' => $this->divide($a, $b),
            'pow' => $this->power($a, $b),
            'mod' => $this->modulo($a, $b),
            default => throw new InvalidArgumentException('Unknown operation!'),
        };
    }

    // Formats a number for display: 20.0 -> "20", 2.5 -> "2.5", 1/3 -> "0.3333"
    public function format(float $value): string
    {
        $text = number_format($value, 4, '.', '');
        $text = rtrim(rtrim($text, '0'), '.');

        return $text === '-0' ? '0' : $text;
    }
}
