<?php

namespace ComBank\OverdraftStrategy;

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
    public function isGrantOverdraftFunds(float $newAmount) : bool {
        return ($this->getOverdraftFundsAmmount()) + $newAmount >= 0;
    }
    public function getOverdraftFundsAmmount() : float {
        return 0.0;
    }
    
}
