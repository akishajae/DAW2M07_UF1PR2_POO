<?php

namespace ComBank\Transactions;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 1:22 PM
 */

use ComBank\Bank\Contracts\BankAccountInterface;
use ComBank\Exceptions\InvalidOverdraftFundsException;
use ComBank\Transactions\Contracts\BankTransactionInterface;

class WithdrawTransaction extends BaseTransaction implements BankTransactionInterface
{

    public function applyTransaction(BankAccountInterface $bankAccount): float
    {

        if (!$bankAccount->getOverdraft()->isGrantOverdraftFunds($this->amount)) {
            throw new InvalidOverdraftFundsException('Your withdraw has reach the max overdraft funds.');
        } else {
            $newBalance = $bankAccount->getBalance() - $this->amount;
        }

        return $newBalance;
    }

    public function getTransactionInfo(): string
    {
        return 'WITHDRAW_TRANSACTION';
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
}
