<?php

namespace ComBank\Support\Traits;

use ComBank\Bank\Contracts\InternationalBankAccount;
use ComBank\Exceptions\InvalidArgsException;
use ComBank\Exceptions\ZeroAmountException;
use ComBank\Transactions\Contracts\BankTransactionInterface;

trait ApiTrait
{

    public function convertBalance(InternationalBankAccount $balance): float
    {
        $url = "https://api.freecurrencyapi.com/v1/latest";

        $curl = curl_init($url);
        $headers = array(
            "apikey: fca_live_oDaP3F3B5bYD7SiQHooud0oXlXg2tYhbHLhPbssH",
        );

        $response = json_decode(curl_exec($curl));

        curl_close($curl);

        return 0.0;
    }
    public function validateEmail($string): bool
    {
        return false;
    }

    public function detectFraud(BankTransactionInterface $bankTransaction): bool
    {
        return false;
    }
}
