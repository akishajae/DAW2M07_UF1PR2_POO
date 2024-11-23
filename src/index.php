<?php

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/27/24
 * Time: 7:24 PM
 */

use ComBank\Bank\BankAccount;
use ComBank\Bank\NationalBankAccount;
use ComBank\Bank\InternationalBankAccount;
use ComBank\Person\Person;
use ComBank\OverdraftStrategy\SilverOverdraft;
use ComBank\Support\Traits\ApiTrait;
use ComBank\Transactions\DepositTransaction;
use ComBank\Transactions\WithdrawTransaction;
use ComBank\Exceptions\BankAccountException;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Exceptions\InvalidOverdraftFundsException;
use ComBank\Exceptions\ZeroAmountException;
use ComBank\Exceptions\InvalidEmailException;
use ComBank\Exceptions\InvalidPhoneNumException;

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
pl('--------- [Start testing national account #3 (No conversion)] --------');
$bankAccount3 = new NationalBankAccount(500.0, null, "€ (EUR)");
pl('My balance : ' . $bankAccount3->getBalance() . $bankAccount3->getCurrency());

//---[Bank account 4]---/
pl('--------- [Start testing international account #4 (Dollar conversion)] --------');
$bankAccount4 = new InternationalBankAccount(300.0, null, "$ (USD)");
pl('My balance : ' . $bankAccount4->getBalance() . $bankAccount3->getCurrency());

pl('Converting balance to Dollars (Rate: 1 $ = ' . $bankAccount4->convertBalance("USD") . ' €)');
pl('Converted balance: ' . $bankAccount4->getConvertedBalance() . $bankAccount4->getConvertedCurrency());

pl('--------- [Start testing valid email] --------');
$person = new Person();

pl('Validating email: pl2023290@365.stucom.com');
try {
    $person->setEmail("pl2023290@365.stucom.com");
} catch (InvalidEmailException $e) {
    pl('Error: ' . $e->getMessage());
}

pl('--------- [Start testing invalid email] --------');

pl('Validating email: pl2023290@invalid-email');
try {
    $person->setEmail("pl2023290@invalid-email");
} catch (InvalidEmailException $e) {
    pl('Error: ' . $e->getMessage());
}

pl('--------- [Start testing valid phone number] --------');

pl('Validating phone number: +44 791 112 4456');
try {
    $person->setPhoneNum("+44 791 112 4456");
} catch (InvalidPhoneNumException $e) {
    pl('Error: ' . $e->getMessage());
}

pl('--------- [Start testing invalid phone number] --------');

pl('Validating phone number: 12345');
try {
    $person->setPhoneNum("12345");
} catch (InvalidPhoneNumException $e) {
    pl('Error: ' . $e->getMessage());
}

$bankAccount5 = new BankAccount(100000);
pl('Doing transaction withdrawal (-1000 with current balance ' . $bankAccount5->getBalance());
$bankAccount5->transaction(new WithdrawTransaction(1000.0));
pl('Doing transaction withdrawal (-2500 with current balance ' . $bankAccount5->getBalance());
$bankAccount5->transaction(new WithdrawTransaction(2500.0));
pl('Doing transaction withdrawal (-5000 with current balance ' . $bankAccount5->getBalance());
$bankAccount5->transaction(new WithdrawTransaction(5000.0));
pl('Doing transaction withdrawal (-10000 with current balance ' . $bankAccount5->getBalance());
$bankAccount5->transaction(new WithdrawTransaction(10000.0));
pl('Doing transaction withdrawal (-20000 with current balance ' . $bankAccount5->getBalance());
$bankAccount5->transaction(new WithdrawTransaction(20000.0));
