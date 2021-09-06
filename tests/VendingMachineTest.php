<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use VendingMachine\VendingMachine;

class VendingMachineTest extends TestCase
{
    public function test_insert_user_credit()
    {
        $vm = new VendingMachine([]);
        $vm->insertUserCredit(0.05);
        $vm->insertUserCredit(0.25);
        $this->assertCount(2, $vm->getUserCredit());
    }

    public function test_insert_invalid_user_credit()
    {
        $vm = new VendingMachine([]);
        $this->expectException(\Exception::class);
        $vm->insertUserCredit(0.5);
    }

    public function test_return_user_credit()
    {
        $vm = new VendingMachine([]);
        $vm->insertUserCredit(0.05);
        $vm->insertUserCredit(0.25);
        $vm->insertUserCredit(1);
        $this->assertCount(3, $vm->returnUserCredit());
        $this->assertEmpty($vm->getUserCredit());
    }


}