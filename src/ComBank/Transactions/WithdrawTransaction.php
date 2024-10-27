<?php

namespace ComBank\Transactions;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 1:22 PM
 */

 use ComBank\Bank\BankAccount;
 use ComBank\Bank\Contracts\BankAccountInterface;
 use ComBank\Exceptions\FailedTransactionException;
 use ComBank\Exceptions\InvalidOverdraftFundsException;
 use ComBank\OverdraftStrategy\NoOverdraft;
 use ComBank\OverdraftStrategy\SilverOverdraft;
 use ComBank\Transactions\Contracts\BankTransactionInterface;

class WithdrawTransaction extends BaseTransaction implements BankTransactionInterface
{

    public function applyTransaction(BankAccountInterface $bankAccount): float
    {

        if ($bankAccount->getOverdraft()->isGrantOverdraftFunds($bankAccount->getBalance() - $this->amount)) {

            $newBalance = $bankAccount->getBalance() - $this->amount;
            $bankAccount->setBalance($newBalance);

            return $newBalance;
        }

        if ($bankAccount->getOverdraft()->getOverdraftFundsAmmount() == NoOverdraft::OVERDRAFT_FUNDS_AMOUNT) {
            throw new InvalidOverdraftFundsException('Insufficient balance to complete the withdrawal.');
        }

        throw new FailedTransactionException('Withdrawal exceeds overdraft limit.');
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
