<?php

namespace ComBank\Bank\Contracts;

use ComBank\Bank\BankAccount;


class InternationalBankAccount extends BankAccount{

    public function getConvertedBalance() : float {
        $this->convertBalance($this);
        return 0.0;
    }

    public function getConvertedCurrency() : string {
        return "";
    }
}