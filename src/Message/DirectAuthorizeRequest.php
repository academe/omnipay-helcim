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
        // if validate() will look in there for it. (see sagepay for an example of how it works)
        $this->validate('amount'); //, 'cardNumber', 'expiryDate', 'cvvIndicator', 'cvv');

        // Get authentication card, billing and cart data.
        $data = array_merge(
            $this->getDirectBaseData(),
            $this->getCardData(),
            $this->getBillingData(),
            $this->getBillingAddressData(),
            $this->getShippingAddressData(),
            $this->getCartData()
        );

        return $data;
    }

    /**
     * The response to sending the request is a text list of name=value pairs.
     */
    public function sendData($data)
    {
        // Send POST to the gateway.
        $this->setMethod('POST');

        $httpResponse = $this
            ->httpClient
            ->post($this->getEndpoint(), [], $data)
            ->send();

        return $this->createResponse($httpResponse->getBody());
    }

    /**
     * Allows responses from the abstract request to be overridden.
     */
    protected function createResponse($data)
    {
        return $this->response = new DirectResponse($this, $data);
    }
}

