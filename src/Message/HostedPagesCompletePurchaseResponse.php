<?php

namespace Omnipay\Helcim\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
* Helcim Hosted Page Complete Authorize Response
*/
class HostedPagesCompletePurchaseResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        // 1 = approved, 0 = declined
        return isset($this->data['response']) && $this->data['response'] == "1";
    }

    public function getTransactionReference()
    {
        return isset($this->data['transactionId']) ? $this->data['transactionId'] : null;
    }

    public function getMessage()
    {
        return isset($this->data['responseMessage']) ? $this->data['responseMessage'] : null;

        /*
            // Example data from $this->data (some additional, optional variables too).
            Array
            (
                [orderId] => 1407770695276
                [response] => 1
                [responseMessage] => APPROVED
                [date] => 2014-08-11
                [time] => 09:24:55
                [cardholderName] => 
                [amount] => 44.99
                [cardNumber] => 4242****4242
                [cardToken] => 53e8e048d04a9460301300
                [transactionId] => 112394664
                [expiryDate] => 0116
                [cardType] => Visa
                [avsResponse] => N
                [cvvResponse] => M
                [approvalCode] => 
            )
        */
    }
}

