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
    public function isGrantOverdraftFunds(float $amount) : bool {
        return $amount > 0;
    }
    public function getOverdraftFundsAmmount() : float {
        return $this->getOverdraftFundsAmmount();
    }
    
}
