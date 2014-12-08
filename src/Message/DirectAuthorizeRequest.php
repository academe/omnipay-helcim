<?php

namespace Omnipay\Helcim\Message;

/**
* Helcim Direct Direct Authorize Request
*/
class DirectAuthorizeRequest extends AbstractRequest
{
    protected $action = 'preauth';
    protected $mode = 'direct';

    protected $endpointPathHistory = '/api/';
    protected $endpointPathActions = '/';

    /**
     * Get the path for the API.
     */
    public function getPath()
    {
        // The Direct entry point works only with POST, and the search
        // entry point can work with either, so we stick with POST.

        // The search (aka history) API has a different entry point to
        // all the other Direct actions. It also supports both GET and POST.

        if ($this->action == 'search') {
            $path = $this->endpointPathHistory;
        } else {
            $this->setMethod('POST');
            $path = $this->endpointPathActions;
        }

        return $path;
    }

    /**
     * Collect the data together to sent to the Gateway.
     */
    public function getData()
    {
        // Some of this mandatory data will be in the card (more accuractely, the card OR the
        // tokenised card.
        // The amount of zero is allowed if the allowZeroAmount flag is set. Omnipay does not
        // allow a zero value in float form. See this ticket raised about this issue:
        // https://github.com/thephpleague/omnipay-common/issues/13
        // (it looks like the amount MUST be submitted as a string with a DP if zero is needed)

        $this->validate('amount');

        // Get authentication card, billing and cart data.
        $data = array_merge(
            $this->getDirectBaseData(),
            $this->getCardData(),
            $this->getBillingData(),
            $this->getBillingAddressData(),
            $this->getShippingAddressData(),
            $this->getCartData(),
            $this->getAvsData()
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
