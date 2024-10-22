<?php namespace ComBank\OverdraftStrategy;

      use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 1:39 PM
 */

/**
 * @description: Grant 100.00 overdraft funds.
 * */
class SilverOverdraft implements OverdraftInterface
{
    const OVERDRAFT_FUNDS_AMOUNT = 100.0;

    // OverdraftInterface
    public function isGrantOverdraftFunds(float $newAmount) : bool {
        return ($this->getOverdraftFundsAmmount()) + $newAmount <= 0;
    }
    public function getOverdraftFundsAmmount() : float {
        return 100.0;
    }
    
}
