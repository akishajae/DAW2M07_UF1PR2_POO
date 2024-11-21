<?php

namespace ComBank\Support\Traits;

use ComBank\Bank\InternationalBankAccount;
use ComBank\Exceptions\InvalidArgsException;
use ComBank\Exceptions\ZeroAmountException;
use ComBank\Transactions\Contracts\BankTransactionInterface;

trait ApiTrait
{

    public function convertBalance(InternationalBankAccount $account): float
    {
        $url = "https://api.freecurrencyapi.com/v1/latest?apikey=fca_live_oDaP3F3B5bYD7SiQHooud0oXlXg2tYhbHLhPbssH&currencies=USD&base_currency=EUR";

        $curl = curl_init($url);
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true
        ]);

        $response = curl_exec($curl);

        curl_close($curl);

        // calculation

       $rate = json_decode($response, true)["data"]["USD"];

        $convertedBalance = $account->getBalance() * $rate;

        return $convertedBalance;
    }
    public function validateEmail($string): bool
    {
        $url = 'https://api-bdc.net/data/email-verify?emailAddress=' . urlencode($string) . '&key=bdc_2374c399e1c7416693720b149516e622';
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
            CURLOPT_SSL_VERIFYPEER => false,
        ));

        $response = json_decode(curl_exec($curl), true);

        $isValid = $response["isValid"];
        $isSyntaxValid = $response["isSyntaxValid"];
        $isMailServerDefined = $response["isMailServerDefined"];
        $isKnownSpammerDomain = $response["isKnownSpammerDomain"];
        $isDisposable = $response["isDisposable"];

        curl_close($curl);

        return $isValid && $isSyntaxValid && $isMailServerDefined && !$isKnownSpammerDomain && !$isDisposable;
    }

    public function detectFraud(BankTransactionInterface $bankTransaction): bool
    {
        return false;
    }
}
