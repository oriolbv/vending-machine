<?php


namespace VendingMachine\Domain;

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

    public static function make(Coin ...$coins): Wallet
    {
        return new self(...$coins);
    }

    public function addCoin(Coin $coin): void
    {
        $this->coins[] = $coin;
    }




    public function getTotalAmount(): int
    {
        return array_reduce($this->coins, function (int $total, Coin $coin) {
            return $total + $coin->getPrice();
        }, 0);
    }

    public function merge(CoinCollection $coins): void
    {
        $coins = array_merge($coins->coins, $this->coins);

        $this->coins = $coins;
    }

    public function removeCoin(array $change): void
    {
        foreach ($change as $changeCoin) {
            foreach ($this->coins as $index => $coin) {
                if ($coin->getPrice() === $changeCoin->getPrice()) {
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

    public function changeFor(int $amount): array
    {
        $changeCoins = CoinCollection::empty();

        foreach ($this->coins as $change) {
            if ($change->getPrice() > $amount) {
                continue;
            }

            $numberOfCoins = (int) ($amount / $change->getPrice());

            $it = $numberOfCoins;
            while ($it > 0) {
                $changeCoins->addCoin($change);
                --$it;
            }

            $amount -= $change->getPrice() * $numberOfCoins;

            if ($amount === 0) {
                return $changeCoins->getCoins();
            }
        }

        if (0 < $amount) {
            throw new NotEnoughChangeException();
        }

        return $changeCoins->getCoins();
    }

    public function getCoins(): array
    {
        return $this->coins;
    }

}