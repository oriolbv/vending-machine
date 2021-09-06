<?php

namespace VendingMachine;


class VendingMachine
{
    private const VALID_COINS = [0.05, 0.10, 0.25, 1];

    private array $userCredit = [];

    public function __construct(array $userCredit)
    {
        $this->userCredit = $userCredit;
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

    public function getUserCredit() : array
    {
        return $this->userCredit;
    }

}