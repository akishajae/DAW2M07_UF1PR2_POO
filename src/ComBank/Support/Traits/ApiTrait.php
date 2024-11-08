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
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.currencyfreaks.com/v2.0/rates/latest?base=eur&symbols=eur,usd&apikey=6c56e1d8fc804cf29d959a0ffee66207',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;

        return json_encode($response);
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
