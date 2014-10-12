<?php

namespace Omnipay\Helcim\Message;

/**
 * Direct accesas to the refund action, to refund funds that
 * have already been paid.
 */

class DirectRefundRequest extends DirectCaptureRequest
{
    protected $action = 'refund';
    protected $mode = 'direct';

    /**
     * Collect the data together to sent to the Gateway.
     */
    public function getData()
    {
        // Some of this mandatory data will be in the card
        $this->validate('amount');

        // Get authentication, card and amount data.

        $data = array_merge(
            $this->getDirectBaseData(),
            [
                'amount' => $this->getAmount(),
            ],
            $this->getCardData()
        );

        return $data;
    }
}
