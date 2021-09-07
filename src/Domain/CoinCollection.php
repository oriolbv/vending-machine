<?php


namespace VendingMachine\Domain;

use VendingMachine\Domain\Exception\NotEnoughChangeException;

class CoinCollection
{
    private array $coins;

    private function __construct(Coin ...$coin)
    {
        $this->coins = $coin;
    }

    public static function empty(): CoinCollection
    {
        return new self();
    }

    public static function make(Coin ...$coins): CoinCollection
    {
        return new self(...$coins);
    }

    public function addCoin(Coin $coin): void
    {
        $this->coins[] = $coin;
    }




    public function getTotalAmount(): float
    {
        return array_reduce($this->coins, function (float $total, Coin $coin) {
            return $total + $coin->getPrice();
        }, 0);
    }

    public function merge(CoinCollection $coins): void
    {
        $coins = array_merge($coins->coins, $this->coins);

        $this->coins = $coins;
    }

    public function removeCoins(array $change): void
    {
        foreach ($change as $changeCoin) {
            foreach ($this->coins as $index => $coin) {
                if ($coin->getPrice() === $changeCoin) {
                    unset($this->coins[$index]);
                    sort($this->coins);
                    break;
                }
            }
        }
    }

    public function removeAllCoins(): void
    {
        $this->coins = [];
    }

    public function changeFor(float $amount): array
    {
        $changeCoins = [];
        $availableCoins = [];
        foreach ($this->coins as $coin) {
            $availableCoins[] = $coin->getPrice();
        }
        rsort($availableCoins);

        foreach ($availableCoins as $change) {
            if ($amount < $change) {
                continue;
            }

            $changeCoins[] = $change;

            $amount = $amount - $change;
            $amount = round($amount, 2);
            if ($amount == 0) {
                $this->removeCoins($changeCoins);
                return $changeCoins;
            }
        }

        if (0 < $amount) {
            throw new NotEnoughChangeException();
        }
        return $changeCoins;
    }

    public function getCoins(): array
    {
        return $this->coins;
    }

}