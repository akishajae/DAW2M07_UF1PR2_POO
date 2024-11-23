<?php

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/27/24
 * Time: 7:24 PM
 */

use ComBank\Bank\BankAccount;
use ComBank\Bank\InternationalBankAccount;
use ComBank\Person\Person;
use ComBank\OverdraftStrategy\SilverOverdraft;
use ComBank\Transactions\DepositTransaction;
use ComBank\Transactions\WithdrawTransaction;
use ComBank\Exceptions\BankAccountException;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Exceptions\InvalidOverdraftFundsException;
use ComBank\Exceptions\ZeroAmountException;

require_once 'bootstrap.php';


//---[Bank account 1]---/
// create a new account1 with balance 400
pl('--------- [Start testing bank account #1, No overdraft] --------');
try {

    // initialize account
    $bankAccount1 = new BankAccount(400.0);

    // show balance account
    pl('My balance : ' . $bankAccount1->getBalance());

    // close account
    $bankAccount1->closeAccount();

    // reopen account
    $bankAccount1->reopenAccount();

    // deposit +150 
    pl('Doing transaction deposit (+150) with current balance ' . $bankAccount1->getBalance());

    $bankAccount1->transaction(new DepositTransaction(150.0));

    pl('My new balance after deposit (+150) : ' . $bankAccount1->getBalance());

    // withdrawal -25
    pl('Doing transaction withdrawal (-25) with current balance ' . $bankAccount1->getBalance());

    $bankAccount1->transaction(new WithdrawTransaction(25.0));

    pl('My new balance after withdrawal (-25) : ' . $bankAccount1->getBalance());

    // withdrawal -600
    pl('Doing transaction withdrawal (-600) with current balance ' . $bankAccount1->getBalance());

    $bankAccount1->transaction(new WithdrawTransaction(600.0));
} catch (ZeroAmountException $e) {
    pl($e->getMessage());
} catch (BankAccountException $e) {
    pl($e->getMessage());
} catch (FailedTransactionException $e) {
    pl('Error transaction: ' . $e->getMessage());
} catch (InvalidOverdraftFundsException $e) {
    pl('Error transaction: ' . $e->getMessage());
}
pl('My balance after failed last transaction : ' . $bankAccount1->getBalance());

// close account
$bankAccount1->closeAccount();


//---[Bank account 2]---/
pl('--------- [Start testing bank account #2, Silver overdraft (100.0 funds)] --------');
try {

    // initialize account
    $bankAccount2 = new BankAccount(200.0);

    // apply overdraft
    $bankAccount2->applyOverdraft(new SilverOverdraft());

    // show balance account
    pl('My balance : ' . $bankAccount2->getBalance());

    // deposit +100
    pl('Doing transaction deposit (+100) with current balance ' . $bankAccount2->getBalance());

    $bankAccount2->transaction(new DepositTransaction(100.0));

    pl('My new balance after deposit (+100) : ' . $bankAccount2->getBalance());

    // withdrawal -300
    pl('Doing transaction deposit (-300) with current balance ' . $bankAccount2->getBalance());

    $bankAccount2->transaction(new WithdrawTransaction(300.0));

    pl('My new balance after withdrawal (-300) : ' . $bankAccount2->getBalance());

    // withdrawal -50
    pl('Doing transaction deposit (-50) with current balance ' . $bankAccount2->getBalance());

    $bankAccount2->transaction(new WithdrawTransaction(50.0));

    pl('My new balance after withdrawal (-50) with funds : ' . $bankAccount2->getBalance());

    // withdrawal -120
    pl('Doing transaction withdrawal (-120) with current balance ' . $bankAccount2->getBalance());

    $bankAccount2->transaction(new WithdrawTransaction(120.0));
} catch (FailedTransactionException $e) {
    pl('Error transaction: ' . $e->getMessage());
}
pl('My balance after failed last transaction : ' . $bankAccount2->getBalance());

try {
    pl('Doing transaction withdrawal (-20) with current balance : ' . $bankAccount2->getBalance());

    $bankAccount2->transaction(new WithdrawTransaction(20.0));
} catch (FailedTransactionException $e) {
    pl('Error transaction: ' . $e->getMessage());
}
pl('My new balance after withdrawal (-20) with funds : ' . $bankAccount2->getBalance());

// close account 
$bankAccount2->closeAccount();

try {
    // close account again
    $bankAccount2->closeAccount();
} catch (BankAccountException $e) {
    pl($e->getMessage());
}

//---[Bank account 3]---/
pl('--------- [Start testing bank account #3, International bank account] --------');

// initialize account
$bankAccount3 = new InternationalBankAccount(100000.0, null, "$");
pl($bankAccount3->getBalance());
echo "Converted balance: " . $bankAccount3->getConvertedBalance();

$bankAccount3->transaction(new DepositTransaction(5000.0));
pl($bankAccount3->getBalance());
$bankAccount3->transaction(new DepositTransaction(2000.0));
$bankAccount3->transaction(new DepositTransaction(10000.0));
$bankAccount3->transaction(new DepositTransaction(22000.0));
pl($bankAccount3->getBalance());
$bankAccount3->transaction(new DepositTransaction(50000.0));
pl($bankAccount3->getBalance());

echo "<br>withdraw<br>";

$bankAccount3->transaction(new WithdrawTransaction(1000.0));
pl($bankAccount3->getBalance());
$bankAccount3->transaction(new WithdrawTransaction(100.0));
$bankAccount3->transaction(new WithdrawTransaction(2500.0));

echo "<br>should be blocked<br>";

$bankAccount3->transaction(new WithdrawTransaction(3000.0));
pl($bankAccount3->getBalance());
$bankAccount3->transaction(new WithdrawTransaction(5000.0));
pl($bankAccount3->getBalance());
$bankAccount3->transaction(new WithdrawTransaction(15000.0));


$person = new Person("Persona", "1", "pl2023290@gmail.com");
echo "Email: " . $person->getEmail();

echo "<br> Test e-mail: test@no-exists.com<br>";
$person->setEmail("test@no-exists.com");