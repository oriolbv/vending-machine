<?php

namespace VendingMachine;

class Item
{
    private string $name;
    private int $price;

    private function __construct(string $name, int $price)
    {
        $this->name = $name;
        $this->price = $price;
    }

    public static function make(string $name, int $price): Item
    {
        return new self($name, $price);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): int
    {
        return $this->price;
    }
}
