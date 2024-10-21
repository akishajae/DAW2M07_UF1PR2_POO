<?php

namespace ComBank\OverdraftStrategy;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 12:27 PM
 */

class NoOverdraft
{

    // OverdraftInterface
    public function isGrantOverdraftFunds(float $newAmount) : bool {
        return ($this->getOverdraftFundsAmmount()) + $newAmount >= 0;
    }
    public function getOverdraftFundsAmmount() : float {
        return 0.0;
    }
    
}
