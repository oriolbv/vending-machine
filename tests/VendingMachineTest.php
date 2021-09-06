<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use VendingMachine\VendingMachine;
use VendingMachine\Item;
use VendingMachine\ItemCollection;

class VendingMachineTest extends TestCase
{
    public function test_insert_user_credit()
    {
        $vm = new VendingMachine([], ItemCollection::empty());
        $vm->insertUserCredit(0.05);
        $vm->insertUserCredit(0.25);
        $this->assertCount(2, $vm->getUserCredit());
    }

    public function test_insert_invalid_user_credit()
    {
        $vm = new VendingMachine([], ItemCollection::empty());
        $this->expectException(\Exception::class);
        $vm->insertUserCredit(0.5);
    }

    public function test_return_user_credit()
    {
        $vm = new VendingMachine([], ItemCollection::empty());
        $vm->insertUserCredit(0.05);
        $vm->insertUserCredit(0.25);
        $vm->insertUserCredit(1);
        $this->assertCount(3, $vm->returnUserCredit());
        $this->assertEmpty($vm->getUserCredit());
    }

    public function test_sell_item_not_enough_money()
    {
        $item = Item::make("Juice", 1.0);
        $vm = new VendingMachine([], new ItemCollection($item));
        $vm->insertUserCredit(0.25);
        $this->expectException(\Exception::class);
        $vm->sellItem("Juice");
    }

    #public function test_sell_item_no_change()
    #{
    #    $item = new Item("Juice", 1.0);
    #    $vm = new VendingMachine([]);
    #    $vm->insertMoney(1);
    #    $vm->sellItem("Juice");
    #    $this->assertEmpty($vm->getItems());
    #}


}