<?php

namespace ComBank\Transactions;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 11:30 AM
 */

use ComBank\Bank\Contracts\BankAccountInterface;
use ComBank\Support\Traits\AmountValidationTrait;
use ComBank\Transactions\Contracts\BankTransactionInterface;

class DepositTransaction extends BaseTransaction implements BankTransactionInterface
{

    use AmountValidationTrait;
    public function applyTransaction(BankAccountInterface $bankAccount): float
    {
        if (!$this->validateAmount($this->amount)) {
            
        }
        return $bankAccount->getBalance() + $this->amount;
    }

    public function getTransactionInfo(): string
    {
        return 'DEPOSIT_TRANSACTION';
    }

    public function getAmount() : float {
        return $this->amount;
    }
}
