<?php

namespace ComBank\OverdraftStrategy;

use ComBank\Bank\BankAccount;
use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 12:27 PM
 */

class NoOverdraft implements OverdraftInterface
{

    const OVERDRAFT_FUNDS_AMOUNT = 0.0;

    // OverdraftInterface
    public function isGrantOverdraftFunds(float $newAmount): bool
    {
        return $newAmount >= ($this->getOverdraftFundsAmmount());
    }
    public function getOverdraftFundsAmmount(): float
    {
        return self::OVERDRAFT_FUNDS_AMOUNT;
    }
}
