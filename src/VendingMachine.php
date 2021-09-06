<?php

namespace VendingMachine;


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

    public function insertUserCredit(float $coin) : void
    {
        $this->userCredit->addCoin(Coin::make($coin));
    }

    public function insertMachineCredit(float $coin) : void
    {
        $this->machineCredit->addCoin(Coin::make($coin));
    }

    public function returnUserCredit() : array
    {
        $tmp_arr = $this->userCredit->getCoins();
        $this->userCredit->removeAllCoins();
        return $tmp_arr;
    }

    public function sellItem(string $name) : array
    {
        $item = $this->itemsAvailable->findItem($name);

        $total_user_credit = $this->userCredit->getTotalAmount();
        $operationChange = [];

        if ($total_user_credit === $item->getPrice()) {
            $this->itemsAvailable->removeItem($item);
            return $operationChange;
        }

        if ($total_user_credit < $item->getPrice()) {
            throw new \Exception("Not enough money.");
        }
        $this->itemsAvailable->removeItem($item);


        return $operationChange;
    }

    public function getUserCredit() : array
    {
        return $this->userCredit->getCoins();
    }

    public function getMachineCredit() : array
    {
        return $this->machineCredit->getCoins();
    }

    public function getItemsAvailable() : array
    {
        return $this->itemsAvailable->getItems();
    }

}