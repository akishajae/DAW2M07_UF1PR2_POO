<?php namespace ComBank\Bank;

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
use ComBank\Bank\Contracts\BackAccountInterface;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Exceptions\InvalidOverdraftFundsException;
use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;
use ComBank\Support\Traits\AmountValidationTrait;
use ComBank\Transactions\Contracts\BankTransactionInterface;

class BankAccount{

    // Properties
    private $balance;
    private $status;
    private $overdraft;

    // Constructor
    function __construct($balance, $status, $overdraft) {
        $this->balance = $balance;
        $this->status = $status;
        $this->overdraft = $overdraft;
    }

    // BankAccountInterface
    public function transaction(BankTransactionInterface $bankTransactionInterface) : void {

    }
    public function openAccount() : bool {
        return false;
    }
    public function reopenAccount() : void {

    }
    public function closeAccount() : void {

    }
    public function getBalance() : float {
        return $this->balance;
    }
    public function getOverdraft() : OverdraftInterface {
        return $this->overdraft;
    }
    public function applyOverdraft(OverdraftInterface $overdraftInterface) : void {

    }
    public function setBalance(float $balance) : void {
        
    }
}


