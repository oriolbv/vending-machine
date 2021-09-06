<?php

namespace VendingMachine\Domain;

class Coin
{
    private const VALID_MONEY = [0.05, 0.10, 0.25, 1];
    private float $price;

    private function __construct(float $price)
    {
        if (!in_array($price, self::VALID_MONEY)) {
            throw new \Exception("Coin value not valid.");
        }
        $this->price = $price;
    }

    public static function make(float $value)
    {
        return new self($value);
    }

    public function getPrice(): float
    {
        return $this->price;
    }
}