<?php

namespace Omnipay\Helcim\Message;

/**
 * Helcim Hosted Pages Authorize Request
 */
class HostedPagesAuthorizeRequest extends AbstractRequest
{
    protected $action = 'preauth';
    protected $mode = 'hostedpages';

    public function getData()
    {
        // Get the base data for the Hosted Page.

        $data = $this->getHostedPagesBaseData();

        $this->validate('amount');

        // Add the test flag only if we are testing.
        // Note that this is different to and independant of developer mode.

        if ($this->getTestMode()) {
            $data['test'] = '1';
        }

        // Everything else is optional.
        // Some fields will come from the shipping and billing address. Others are for this gateway only.
        // If we have more than a few basic parameters, then switch the method automatically to POST.
        // The hosted pages option has no return URL (that is coded into the form in advance) and has
        // no cancel URL. There is also no callback URL to feed the results in through a back-channel.

        $data = array_merge($data, $this->getBillingData());

        // Waiting for https://github.com/omnipay/common/issues/11 fix.

        $data = array_merge($data, $this->getCartData());

        return $data;
    }

    /**
     * The response to "sending" the data is a redirect object.
     */
    public function sendData($data)
    {
        return $this->response = $this->createResponse($data, $this->getEndpoint(), $this->getMethod());
    }

    /**
     * Create the response object.
     */
    public function createResponse($data, $endpoint, $method)
    {
        return new HostedPagesResponse($this, $data, $endpoint, $method);
    }
}

