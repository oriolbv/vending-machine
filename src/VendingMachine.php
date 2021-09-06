<?php

namespace VendingMachine;


class VendingMachine
{
    private const VALID_COINS = [0.05, 0.10, 0.25, 1];

    private array $userCredit = [];
    private ItemCollection $itemsAvailable;

    public function __construct(array $userCredit, ItemCollection $itemsAvailable)
    {
        $this->userCredit = $userCredit;
        $this->itemsAvailable = $itemsAvailable;
    }

    public function insertUserCredit(float $coin) : void
    {
        if (!in_array($coin, self::VALID_COINS)) {
            throw new \Exception("Coin value not valid.");
        }
        array_push($this->userCredit, $coin);
    }

    public function returnUserCredit() : array
    {
        $tmp_arr = $this->userCredit;
        $this->userCredit = [];
        return $tmp_arr;
    }



    public function sellItem(string $name) : array
    {
        $item = $this->itemsAvailable->find($name);

        $total_user_credit = (float) array_reduce($this->userCredit, function ($total, $coin) {
            return $coin + $total;
        }, 0);
        if ($total_user_credit < $item->getPrice()) {
            throw new \Exception("Not enough money.");
        }

        return [];
    }

    public function getUserCredit() : array
    {
        return $this->userCredit;
    }

    public function getItemsAvailable() : array
    {
        return $this->itemsAvailable;
    }

}