<?php

namespace Omnipay\Helcim\Message;

/**
* Helcim Direct Direct Authorize Request
*/
class DirectAuthorizeRequest extends AbstractRequest
{
    protected $action = 'preauth';
    protected $mode = 'direct';

    /**
     * Collect the data together to sent to the Gateway.
     */
    public function getData()
    {
        // Some of this mandatory data will be in the card, and I'm not sure
        // if validate() will look in there for it.
        $this->validate('amount'); //, 'cardNumber', 'expiryDate', 'cvvIndicator', 'cvv');

        // Get authentication card, billing and cart data.
        $data = array_merge(
            $this->getDirectBaseData(),
            $this->getCardData(),
            $this->getBillingData(),
            $this->getCartData()
        );

        return $data;
    }

    /**
     * The response to sending the data is the XML data.
     */
    public function sendData($data)
    {
        // Send the POST to the gateway.
        // CHECKME: is $this->getMethod() relevant here? I would say it isn't.
        $httpResponse = $this
            ->httpClient
            ->post($this->getEndpoint(), null, $data)
            ->send();

        return $this->createResponse($httpResponse->xml());
    }

    /**
     * Allows responses from the abstract request to be overridden.
     */
    protected function createResponse($data)
    {
        return $this->response = new DirectAuthorizeResponse($this, $data);
    }
}

