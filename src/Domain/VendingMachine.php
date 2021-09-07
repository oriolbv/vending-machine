<?php

namespace VendingMachine\Domain;


use VendingMachine\Domain\Exception\ItemSoldOutException;
use VendingMachine\Domain\Exception\NotEnoughChangeException;
use VendingMachine\Domain\Exception\NotEnoughCreditException;

class VendingMachine
{
    private const VALID_COINS = [0.05, 0.10, 0.25, 1];

    private CoinCollection $userCredit;     // Credit inserted by the actual client
    private CoinCollection $machineCredit;  // Credit used to return change to the clients
    private ItemCollection $itemsAvailable;

    public function __construct(CoinCollection $userCredit,
                                CoinCollection $machineCredit,
                                ItemCollection $itemsAvailable)
    {
        $this->userCredit = $userCredit;
        $this->machineCredit = $machineCredit;
        $this->itemsAvailable = $itemsAvailable;
    }

    public static function empty(): VendingMachine
    {
        return new self(CoinCollection::empty(), CoinCollection::empty(), ItemCollection::empty());
    }

    public function insertUserCredit(float $coin) : void
    {
        $this->userCredit->addCoin(Coin::make($coin));
    }

    public function insertMachineCredit(float $coin) : void
    {
        $this->machineCredit->addCoin(Coin::make($coin));
    }

    public function insertItem(string $name, float $price)
    {
        $this->itemsAvailable->addItem(Item::make($name, $price));
    }

    public function returnUserCredit() : array
    {
        $tmp_arr = $this->userCredit->getCoins();
        $this->userCredit->removeAllCoins();
        $prices = [];
        foreach ($tmp_arr as $coin) {
            $prices[] = $coin->getPrice();
        }
        return $prices;
    }

    public function sellItem(string $name) : array
    {
        try {
            $item = $this->itemsAvailable->findItem($name);
        } catch (\Exception $e) {
            throw new ItemSoldOutException();
        }
        
        $total_user_credit = $this->userCredit->getTotalAmount();
        if ($total_user_credit < $item->getPrice()) {
            throw new NotEnoughCreditException();
        }

        $operationChange = [];
        if ($total_user_credit === $item->getPrice()) {
            $this->itemsAvailable->removeItem($item);
            $this->userCredit = CoinCollection::empty();
            return $operationChange;
        }

        try {
            $operationChange = $this->machineCredit->changeFor($total_user_credit - $item->getPrice());
        } catch (\Exception $e) {
            throw new NotEnoughChangeException();
        }

        $this->itemsAvailable->removeItem($item);
        $this->userCredit = CoinCollection::empty();
        return $operationChange;
    }

    public function getUserCredit() : array
    {
        return $this->userCredit->getCoins();
    }

    public function getMachineCredit() : array
    {
        $coinPrices = [];
        foreach ($this->machineCredit->getCoins() as $coin) {
            $coinPrices[] = $coin->getPrice();
        }
        return $coinPrices;
    }

    public function getItemsAvailable() : array
    {
        $item_names = [];
        foreach ($this->itemsAvailable->getItems() as $item) {
            $item_names[] = $item->getName();
        }
        return $item_names;
    }

}