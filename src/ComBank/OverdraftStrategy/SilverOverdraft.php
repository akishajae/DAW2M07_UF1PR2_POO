<?php namespace ComBank\OverdraftStrategy;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 1:39 PM
 */

/**
 * @description: Grant 100.00 overdraft funds.
 * */
class SilverOverdraft 
{

    // OverdraftInterface
    public function isGrantOverdraftFunds(float $newAmount) : bool {
        return ($this->getOverdraftFundsAmmount()) + $newAmount >= 0;
    }
    public function getOverdraftFundsAmmount() : float {
        return 100.0;
    }
    
}
