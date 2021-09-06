<?php

namespace VendingMachine\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use VendingMachine\Domain\Coin;
use VendingMachine\Domain\VendingMachine;


class RunVendingMachine extends Command
{
    protected static $defaultName = 'run';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Vending Machine');

        $vm = VendingMachine::empty();

        while (true) {
            $operation = $this->askOperation($input, $output);

            if ($operation === 'insert_money') {
                $coin = $this->askCoin($input, $output);
                $vm->insertUserCredit((float)$coin);
            }

            if ($operation === 'return_coin') {
                $user_credit = $vm->returnUserCredit();
                $output->write("[".implode(",", $user_credit)."]\n");
            }

            if ($operation === 'select_item') {
                $item = $this->askService($input, $output);
                $vm->sellItem($item);
            }

            if ($operation === 'service') {
                $service = $this->askService($input, $output);
                if ($service === "Check change money") {
                    # Check change money
                    $changeMoney = $vm->getMachineCredit();
                    $output->write("Change money: " . "[".implode(",", $changeMoney)."]" ."\n");
                } elseif ($service === "Check available items") {
                    # Check available items
                    $itemsAvailable = $vm->getItemsAvailable();
                    $output->write("Available items: " . "[".implode(",", $itemsAvailable)."]" ."\n");
                } elseif ($service === "Add change money") {
                    # Add change money
                    $output->write("Add change money\n");
                    $coin = $this->askCoin($input, $output);
                    $vm->insertMachineCredit((float)$coin);
                } else {
                    # Add items
                    $output->write("Add items\n");
                    $item = $this->askItem($input, $output);
                    if ($item === "Water") {
                        $vm->insertItem($item, 0.65);
                    } elseif ($item === "Juice") {
                        $vm->insertItem($item, 1);
                    } else {
                        $vm->insertItem($item, 1.50);
                    }

                }
            }
        }
    }

    protected function askOperation($input, $output)
    {
        $validOperations = [
            'insert_money',
            'return_coin',
            'select_item',
            'service',
        ];

        $question = new ChoiceQuestion('Please select an operation', $validOperations);
        $question->setErrorMessage('Please select a valid operation');

        $helper = $this->getHelper('question');
        return $helper->ask($input, $output, $question);
    }

    private function askCoin(InputInterface $input, OutputInterface $output)
    {
        $validCoins = [
            '0.05',
            '0.10',
            '0.25',
            '1.00',
        ];

        $question = new ChoiceQuestion('Add a coin', $validCoins);
        $question->setErrorMessage('Please select a valid coin');

        $helper = $this->getHelper('question');
        return $helper->ask($input, $output, $question);
    }

    private function askService(InputInterface $input, OutputInterface $output)
    {
        $validServices = [
            'Check change money',
            'Check available items',
            'Add change money',
            'Add items',
        ];

        $question = new ChoiceQuestion('Select service', $validServices);
        $question->setErrorMessage('Please select a valid service');

        $helper = $this->getHelper('question');
        return $helper->ask($input, $output, $question);
    }

    private function askItem(InputInterface $input, OutputInterface $output)
    {
        $validItems = [
            'Water',
            'Juice',
            'Soda'
        ];

        $question = new ChoiceQuestion('Select item', $validItems);
        $question->setErrorMessage('Please select a valid item');

        $helper = $this->getHelper('question');
        return $helper->ask($input, $output, $question);
    }
}
