<?php namespace ComBank\Support\Traits;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 2:35 PM
 */

use ComBank\Exceptions\InvalidArgsException;
use ComBank\Exceptions\ZeroAmountException;

trait AmountValidationTrait
{
    /**
     * @param float $amount
     * @throws InvalidArgsException
     * @throws ZeroAmountException
     */
    public function validateAmount(float $amount):void
    {
        if (!filter_var($amount, FILTER_VALIDATE_FLOAT)) {
            throw new InvalidArgsException('Error. Insert a valid input.');
        }

        if ($amount <= 0) {
            throw new ZeroAmountException('Error. Insert an amount higher than 0.');
        }
    }
}
