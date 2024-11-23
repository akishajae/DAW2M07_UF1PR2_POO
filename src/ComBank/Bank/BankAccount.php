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
use ComBank\Support\Traits\ApiTrait;
use ComBank\Transactions\Contracts\BankTransactionInterface;
use ComBank\Person\Person;

class BankAccount implements BankAccountInterface
{

    // Trait
    use AmountValidationTrait;
    use ApiTrait;

    // Properties
    protected $personHolder;
    protected $balance;
    protected $status;
    protected $overdraft;
    protected $currency;

    // Constructor
    public function __construct(float $newBalance = 0.0, Person $personHolder = null, $currency = null)
    {
        $this->personHolder = $personHolder;
        $this->validateAmount($newBalance);
        $this->balance = $newBalance;
        $this->status = BankAccountInterface::STATUS_OPEN;
        $this->overdraft = new NoOverdraft();
        $this->currency = $currency;
    }

    // BankAccountInterface
    public function transaction(BankTransactionInterface $bankTransaction): void
    {
        if (!$this->isOpen()) {
            throw new BankAccountException('Bank account should be opened.');
        }

        $bankTransaction->applyTransaction($this);
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
        if ($this->isOpen()) {
            throw new BankAccountException('Bank account is already opened.');
        }

        echo '<br>Bank account is now opened. <br>';
        $this->status = BankAccountInterface::STATUS_OPEN;
    }
    public function closeAccount(): void
    {
        if (!$this->isOpen()) {
            throw new BankAccountException('Bank account is already closed.');
        }

        echo '<br>Bank account is now closed. <br>';
        $this->status = BankAccountInterface::STATUS_CLOSED;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function getOverdraft(): OverdraftInterface
    {
        return $this->overdraft;
    }
    public function applyOverdraft(OverdraftInterface $overdraft): void
    {
        $this->overdraft = $overdraft;
    }
    public function setBalance(float $newBalance): void
    {
        $this->balance = $newBalance;
    } 

    /**
     * Get the value of PersonHolder
     */ 
    public function getPersonHolder()
    {
        return $this->personHolder;
    }

    /**
     * Set the value of PersonHolder
     *
     * @return  self
     */ 
    public function setPersonHolder(Person $personHolder)
    {
        $this->personHolder = $personHolder;

        return $this;
    }
    
    /**
     * Get the value of currency
     */ 
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set the value of currency
     *
     * @return  self
     */ 
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }
}
