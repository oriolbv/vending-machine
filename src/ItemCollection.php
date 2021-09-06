<?php

namespace VendingMachine;

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

    public function remove(Item $item) : void
    {
        foreach ($this->items as $idx => $current_item) {
            if ($current_item->getName() === $item->getName()) {
                unset($this->items[$idx]);
                break;
            }
        }
    }

    public function find(string $id) : Item
    {
        $item = null;

        foreach ($this->items as $i) {
            if ($i->getName() === $id) {
                $item = $i;
                break;
            }
        }

        if (!$item) {
            throw new \Exception("Item not found.");
        }

        return $item;

    }

    public function getItems() : array
    {
        return $this->items;
    }

}