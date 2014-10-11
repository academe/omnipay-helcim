<?php

namespace Omnipay\Helcim\Message;

//use Omnipay\Common\Exception\InvalidRequestException;

/**
 * Direct accesas to the capture action, to capture funds that
 * have already been authorized.
 */

class DirectCaptureRequest extends DirectAuthorizeRequest
{
    protected $action = 'capture';
    protected $mode = 'direct';

    public function setTransactionReference($value)
    {
        return $this->setParameter('transactionReference', $value);
    }

    public function getTransactionReference()
    {
        return $this->getParameter('transactionReference');
    }

    /**
     * Collect the data together to sent to the Gateway.
     */
    public function getData()
    {
        // Some of this mandatory data will be in the card
        $this->validate('amount', 'transactionReference');

        // Get authentication card, billing and cart data.
        $data = array_merge(
            $this->getDirectBaseData(),
            [
                'transactionId' => $this->getTransactionReference(),
                'amount' => $this->getAmount(),
            ]
        );

        return $data;
    }
}
