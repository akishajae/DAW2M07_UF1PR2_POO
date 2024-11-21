<?php

namespace ComBank\Support\Traits;

use ComBank\Bank\InternationalBankAccount;
use ComBank\Exceptions\InvalidArgsException;
use ComBank\Exceptions\ZeroAmountException;
use ComBank\Transactions\Contracts\BankTransactionInterface;
use ComBank\Transactions\DepositTransaction;

trait ApiTrait
{

    public function convertBalance(InternationalBankAccount $account)
    {
        $url = "https://api.freecurrencyapi.com/v1/latest?apikey=fca_live_oDaP3F3B5bYD7SiQHooud0oXlXg2tYhbHLhPbssH&currencies=USD&base_currency=EUR";

        $curl = curl_init($url);
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => true
        ));

        $response = json_decode(curl_exec($curl), true);

        curl_close($curl);

        // calculation

        $rate = $response["data"]["USD"];

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

        if (!$isValid) {
            echo "Email is not valid.";
        } elseif (!$isSyntaxValid) {
            echo "Invalid email syntax.";
        } elseif (!$isMailServerDefined) {
            echo "Mail server is not defined.";
        } elseif ($isKnownSpammerDomain) {
            echo "Known spammer domain.";
        } elseif ($isDisposable) {
            echo "Disposable email address.";
        }
        
        return ($isValid && $isSyntaxValid && $isMailServerDefined && !$isKnownSpammerDomain && !$isDisposable);
    }

    public function detectFraud(BankTransactionInterface $bankTransaction): bool
    {
        $url = 'https://673e09cc0118dbfe8609e29c.mockapi.io/detect-fraud/fraud-detection-system';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => true
        ));

        $response = json_decode(curl_exec($curl), true);

        curl_close($curl);

        // operation

        foreach ($response as $key => $value) {

            if ($bankTransaction->getTransactionInfo() == $value["movement"]) {

                $amountTransaction = $bankTransaction->getAmount();

                switch ($value["movement"]) {                    
                    case "DEPOSIT_TRANSACTION":

                        if ($amountTransaction <= ($response[1]["amount"])) {

                            if ($amountTransaction <= $response[0]["amount"]) {
                                echo "<br>Risk score: " . $response[0]["risk"] . "<br>";
                            } elseif ($amountTransaction <= $response[1]["amount"] && $amountTransaction > $response[0]["amount"]) {
                                echo "<br>Risk score: " . $response[1]["risk"] . "<br>";
                            }

                            return true;
                        } elseif ($amountTransaction >= ($response[2]["amount"])) {

                            if ($amountTransaction <= $response[2]["amount"] && $amountTransaction > $response[1]["amount"]) {
                                echo "<br>Risk score: " . $response[2]["risk"] . "<br>";
                            } elseif ($amountTransaction <= $response[3]["amount"] && $amountTransaction > $response[2]["amount"]) {
                                echo "<br>Risk score: " . $response[3]["risk"] . "<br>";
                            }

                            return false;
                        }

                    case "WITHDRAW_TRANSACTION":

                        if ($amountTransaction <= ($response[5]["amount"])) {

                            if ($amountTransaction <= $response[4]["amount"]) {
                                echo "<br>Risk score: " . $response[4]["risk"] . "<br>";
                            } elseif ($amountTransaction <= $response[5]["amount"] && $amountTransaction > $response[4]["amount"]) {
                                echo "<br>Risk score: " . $response[5]["risk"] . "<br>";
                            }

                            return true;
                        } elseif ($amountTransaction >= ($response[6]["amount"])) {

                            if ($amountTransaction <= $response[6]["amount"] && $amountTransaction > $response[5]["amount"]) {
                                echo "<br>Risk score: " . $response[6]["risk"] . "<br>";
                            } elseif ($amountTransaction <= $response[7]["amount"] && $amountTransaction > $response[6]["amount"]) {
                                echo "<br>Risk score: " . $response[7]["risk"] . "<br>";
                            }

                            return false;
                        }
                }
            }
        }

        return false;

        /*
        A possible more efficient option:

        function getRiskScore($amount, $response) {
            for ($i = 0; $i < count($response) - 1; $i++) {
                if ($amount <= $response[$i + 1]["amount"] && $amount > $response[$i]["amount"]) {
                    return $response[$i + 1]["risk"];
                }
            }
            return null;  // If no risk score is found
        }

        $amount = $bankTransaction->getAmount();
        $riskScore = getRiskScore($amount, $response);
        if ($riskScore !== null) {
            echo "<br>Risk score: $riskScore<br>";
        } else {
            echo "<br>No risk score found.<br>";
        }
        
        */
    }
}
