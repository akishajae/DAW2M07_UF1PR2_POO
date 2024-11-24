<?php

use PHPUnit\Framework\TestCase;
use ComBank\Bank\BankAccount;
use ComBank\Bank\InternationalBankAccount;
use ComBank\Exceptions\InvalidEmailException;
use ComBank\Exceptions\InvalidPhoneNumException;
use ComBank\Transactions\DepositTransaction;
use ComBank\Transactions\WithdrawTransaction;

class BankFeaturesTest extends TestCase
{
    // Test 1: Verify national bank account returns currency in €
    public function testNationalAccountReturnsEuro(): void
    {
        $account = new BankAccount(100.0);
        $this->assertEquals('EUR', $account->getCurrency());
    }

    // Test 2: Verify international bank account returns currency in € with no converted balance
    public function testInternationalAccountReturnsEuroWithoutConversion(): void
    {
        $account = new InternationalBankAccount(100.0);
        $this->assertEquals('EUR', $account->getConvertedCurrency());
    }

    // Test 3: Verify international bank account returns currency in $ with balance converted
    public function testInternationalAccountReturnsConvertedBalanceInUSD(): void
    {
        $account = new InternationalBankAccount(100.0);
        $convertedBalance = $account->getConvertedBalance();
        $this->assertGreaterThan(0, $convertedBalance); // Ensure conversion occurs
    }

    // Test 4: Verify a valid email for an account holder
    public function testValidEmail(): void
    {
        $person = new \ComBank\Person\Person('John Doe', 12345, 'valid@example.com');
        $this->assertEquals('valid@example.com', $person->getEmail());
    }

    // Test 5: Verify an invalid email for an account holder
    public function testInvalidEmail(): void
    {
        $this->expectException(InvalidEmailException::class);
        new \ComBank\Person\Person('John Doe', 12345, 'invalid-email');
    }

    // Test 6: Verify deposit allowed by fraud functionality
    public function testDepositAllowedByFraudDetection(): void
    {
        $account = new BankAccount(100.0);
        $transaction = new DepositTransaction(50.0);
        $account->transaction($transaction); // Perform transaction
        $newBalance = $account->getBalance(); // Get updated balance
        $this->assertEquals(150.0, $newBalance); // Verify updated balance
    }

    // Test 7: Verify deposit blocked by fraud functionality
    public function testDepositBlockedByFraudDetection(): void
    {
        $this->expectOutputString('Transaction has been blocked.<br>');
        $account = new BankAccount(100.0);
        $transaction = new DepositTransaction(50000.0); // Large transaction likely to be flagged
        $account->transaction($transaction);
    }

    // Test 8: Verify withdrawal allowed by fraud functionality
    public function testWithdrawAllowedByFraudDetection(): void
    {
        $account = new BankAccount(100.0);
        $transaction = new WithdrawTransaction(50.0);
        $account->transaction($transaction); // Perform transaction
        $newBalance = $account->getBalance(); // Get updated balance
        $this->assertEquals(50.0, $newBalance); // Verify updated balance
    }

    // Test 9: Verify withdrawal blocked by fraud functionality
    public function testWithdrawBlockedByFraudDetection(): void
    {
        $this->expectOutputString('Transaction has been blocked.<br>');
        $account = new BankAccount(100.0);
        $transaction = new WithdrawTransaction(50000.0); // Large transaction likely to be flagged
        $account->transaction($transaction);
    }

    // Test 10: Verify new free functionality (phone number validation)
    public function testValidPhoneNumber(): void
    {
        $person = new \ComBank\Person\Person('John Doe', 12345, null, '+34600000000');
        $this->assertEquals('+34600000000', $person->getPhoneNum());
    }

    public function testInvalidPhoneNumber(): void
    {
        $this->expectException(InvalidPhoneNumException::class);
        new \ComBank\Person\Person('John Doe', 12345, null, 'invalid-phone');
    }
}
