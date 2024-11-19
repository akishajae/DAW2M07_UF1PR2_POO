<?php

namespace ComBank\Bank\Contracts;

use ComBank\Bank\BankAccount;


class InternationalBankAccount extends BankAccount{

    public function getConvertedBalance() : float {
        $balance = $this->convertBalance($this);
        return floatval(number_format($balance, 2, ",", "."));
    }

    public function getConvertedCurrency() : string {
        return "$";
    }
}