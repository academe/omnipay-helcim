<?php

namespace Omnipay\Helcim\Message;

/**
* Helcim Direct Direct Authorize Request
*/
class DirectAuthorizeRequest extends AbstractRequest
{
    protected $action = 'preauth';

    /**
     * Collect the data together to sent to the Gateway.
     */
    public function getData()
    {
        $data = array();

        return $data;
    }

    /**
     * The response to "sending" the data is a redirect object.
     */
    public function sendData($data)
    {
        // Send the POST to the gateway.
        // CHECKME: is $this->getMethod() relevant here? I would say it isn't.
        $httpResponse = $this
            ->httpClient
            ->post($this->getEndpoint(), null, $data)
            ->send();

        return new DirectAuthorizeResponse($this, $httpResponse->getBody());
    }
}

