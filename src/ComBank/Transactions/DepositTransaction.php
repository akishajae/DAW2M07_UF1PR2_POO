<?php

namespace ComBank\Transactions;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 11:30 AM
 */

use ComBank\Bank\Contracts\BankAccountInterface;
use ComBank\Transactions\Contracts\BankTransactionInterface;

class DepositTransaction extends BaseTransaction implements BankTransactionInterface
{

    public function __construct(float $amount) {
    }
    public function applyTransaction(BankAccountInterface $bankAccount): float
    {
        return $bankAccount->getBalance() + $this->amount;
    }

    public function getTransactionInfo(): string
    {
        return 'DEPOSIT_TRANSACTION';
    }

    public function getAmount() : float {
        return 0.0;
    }
}
