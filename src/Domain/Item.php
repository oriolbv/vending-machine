<?php

namespace VendingMachine\Domain;

class Item
{
    private string $name;
    private float $price;

    private function __construct(string $name, float $price)
    {
        $this->name = $name;
        $this->price = $price;
    }

    public static function make(string $name, float $price): Item
    {
        return new self($name, $price);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }
}
