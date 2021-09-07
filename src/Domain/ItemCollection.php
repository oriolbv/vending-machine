<?php

namespace VendingMachine\Domain;

use VendingMachine\Domain\Exception\ItemSoldOutException;

class ItemCollection
{
    private array $items;

    public function __construct(Item ...$items)
    {
        $this->items = $items;
    }

    public static function empty() : ItemCollection
    {
        return new self();
    }

    public function addItem(Item $item) : void
    {
        $this->items[] = $item;
    }

    public function removeItem(Item $item) : void
    {
        foreach ($this->items as $idx => $current_item) {
            if ($current_item->getName() === $item->getName()) {
                unset($this->items[$idx]);
                break;
            }
        }
    }

    public function findItem(string $id) : Item
    {
        $item = null;

        foreach ($this->items as $i) {
            if ($i->getName() === $id) {
                $item = $i;
                break;
            }
        }

        if (!$item) {
            throw new ItemSoldOutException;
        }

        return $item;

    }

    public function getItems() : array
    {
        return $this->items;
    }

}