<?php

namespace ComBank\Bank;

use ComBank\Bank\BankAccount;


class InternationalBankAccount extends BankAccount
{

    public function getConvertedBalance(): float
    {
        $rate = $this->convertBalance("USD");
        $convertedBalance = $this->getBalance() * $rate;
        return floatval(number_format($convertedBalance, 2, ",", "."));
    }

    public function getConvertedCurrency(): string
    {
        return $this->getCurrency();
    }
}
