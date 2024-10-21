<?php

namespace ComBank\Bank;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/27/24
 * Time: 7:25 PM
 */

use ComBank\Exceptions\BankAccountException;
use ComBank\Exceptions\InvalidArgsException;
use ComBank\Exceptions\ZeroAmountException;
use ComBank\OverdraftStrategy\NoOverdraft;
use ComBank\Bank\Contracts\BankAccountInterface;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Exceptions\InvalidOverdraftFundsException;
use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;
use ComBank\Support\Traits\AmountValidationTrait;
use ComBank\Transactions\Contracts\BankTransactionInterface;

class BankAccount implements BankAccountInterface
{

    // Trait
    // por qué está dentro?????
    use AmountValidationTrait;

    // Properties
    private $balance;
    private $status;
    private $overdraft;

    // Constructors
    public function __construct(float $newBalance = 0.0)
    {
        // to do validateAmount
        $this->validateAmount($newBalance);
        $this->balance = $newBalance;
        $this->status = BankAccountInterface::STATUS_OPEN;
        // overdraftInterface??
        $this->overdraft = new NoOverdraft();
    }

    // BankAccountInterface
    public function transaction(BankTransactionInterface $bankTransaction): void
    {
        //  exception
        $newBalance = $bankTransaction->applyTransaction($this);
        $this->setBalance($newBalance);
    }
    public function isOpen(): bool
    {
        if ($this->status == BankAccountInterface::STATUS_OPEN) {
            return true;
        }
        return false;
    }
    public function reopenAccount(): void
    {
        // exception
        $this->status = BankAccountInterface::STATUS_OPEN;
    }
    public function closeAccount(): void
    {
        // exception
        $this->status = BankAccountInterface::STATUS_CLOSED;
    }

    public function getBalance(): float{
        return $this->balance;
    }

    public function getOverdraft(): OverdraftInterface
    {
        return $this->overdraft;
    }
    public function applyOverdraft(OverdraftInterface $overdraftInterface): void {}
    public function setBalance(float $newBalance): void {

    }
}
