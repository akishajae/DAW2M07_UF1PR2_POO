<?php

namespace ComBank\Support\Traits;

use ComBank\Bank\InternationalBankAccount;
use ComBank\Exceptions\InvalidArgsException;
use ComBank\Exceptions\ZeroAmountException;
use ComBank\Exceptions\InvalidEmailException;
use ComBank\Exceptions\InvalidPhoneNumException;

use ComBank\Transactions\Contracts\BankTransactionInterface;
use ComBank\Transactions\DepositTransaction;

trait ApiTrait
{

    public function convertBalance($currency)
    {
        $url = "https://api.freecurrencyapi.com/v1/latest?apikey=fca_live_oDaP3F3B5bYD7SiQHooud0oXlXg2tYhbHLhPbssH&currencies=USD&base_currency=EUR";

        $curl = curl_init($url);
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => true
        ));

        $response = json_decode(curl_exec($curl), true);

        curl_close($curl);

        // calculation

        $rate = $response["data"][$currency];

        return $rate;
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
        // $isMailServerDefined = $response["isMailServerDefined"];
        $isKnownSpammerDomain = $response["isKnownSpammerDomain"];
        $isDisposable = $response["isDisposable"];

        curl_close($curl);

        if (!$isValid) {
            throw new InvalidEmailException('Invalid email address: ' . $string);
        } elseif (!$isSyntaxValid) {
            throw new InvalidEmailException('Invalid email syntax.');
        }
        // elseif (!$isMailServerDefined) {
        //     echo "Mail server is not defined.";
        // } 
        elseif ($isKnownSpammerDomain) {
            throw new InvalidEmailException('Known spammer domain.');
        } elseif ($isDisposable) {
            throw new InvalidEmailException('Disposable email address.');
        }

        return $isValid && $isSyntaxValid && !$isKnownSpammerDomain && !$isDisposable;
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

                        $depositRisk = $this->calculateRisk($amountTransaction, [
                            [$response[0]["amount"], $response[0]["risk"]],
                            [$response[1]["amount"], $response[1]["risk"]],
                            [$response[2]["amount"], $response[2]["risk"]],
                            [$response[3]["amount"], $response[3]["risk"]],
                        ]);

                        if ($depositRisk < $response[2]["risk"]) {
                            echo "Risk score: $depositRisk. <br>";
                            return true;
                        } else {
                            echo "Risk score: $depositRisk. <br>";
                            return false;
                        }

                    case "WITHDRAW_TRANSACTION":

                        $withdrawRisk = $this->calculateRisk($amountTransaction, [
                            [$response[4]["amount"], $response[4]["risk"]],
                            [$response[5]["amount"], $response[5]["risk"]],
                            [$response[6]["amount"], $response[6]["risk"]],
                            [$response[7]["amount"], $response[7]["risk"]],
                        ]);

                        if ($withdrawRisk < $response[6]["risk"]) {
                            echo "Risk score: $withdrawRisk. <br>";
                            return true;
                        } else {
                            echo "Risk score: $withdrawRisk. <br>";
                            return false;
                        }
                }
            }
        }

        return false;
    }

    private function calculateRisk($amountTransaction, $range)
    {
        // amount < 1st range (25/5000) --> risk is calculated based on the 1st range
        if ($amountTransaction < $range[0][0]) {
            $amount1 = 0;
            $risk1 = 0;
            $amount2 = $range[0][0];
            $risk2 = $range[0][1];

            return $risk1 + (($amountTransaction - $amount1) / ($amount2 - $amount1) * ($risk2 - $risk1));
        }

        // amount > 4th range (100/50000) --> risk: 100
        if ($amountTransaction >= $range[count($range) - 1][0]) {
            return $range[count($range) - 1][1];
        }

        // lineal interpolation between ranges
        for ($i = 0; $i < count($range) - 1; $i++) {
            $amount1 = $range[$i][0];
            $risk1 = $range[$i][1];
            $amount2 = $range[$i + 1][0];
            $risk2 = $range[$i + 1][1];

            // in range --> interpolation is applied
            if ($amount1 <= $amountTransaction && $amountTransaction <= $amount2) {
                return $risk1 + (($amountTransaction - $amount1) / ($amount2 - $amount1)) * ($risk2 - $risk1);
            }
        }

        return 0;
    }

    public function validatePhoneNum($string): bool
    {

        $url = 'https://api-bdc.net/data/phone-number-validate?number=' . urlencode($string) . '&countryCode=esp&localityLanguage=es&key=bdc_2374c399e1c7416693720b149516e622';
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

        curl_close($curl);

        if (!$isValid) {
            throw new InvalidPhoneNumException('Invalid phone number: ' . $string);
        }

        return $isValid;
    }
}
