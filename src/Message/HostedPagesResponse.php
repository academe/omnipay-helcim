<?php

namespace Omnipay\Helcim\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Helcim Hosted Pages Authorize Response.
 * This is a "fake" rssponse - it does not come from the remote payment gateway, but is generated
 * locally. It contains the next action, with may be a redirect to the hosted payment page.
 */
class HostedPagesResponse extends AbstractResponse implements RedirectResponseInterface
{
    protected $redirectUrl;

    public function __construct(RequestInterface $request, $data, $redirectUrl, $method)
    {
        $this->request = $request;
        $this->data = $data;
        $this->redirectUrl = $redirectUrl;
        $this->method = $method;
    }

    public function isSuccessful()
    {
        // False so the calling applicaton knows that the transaction is not yet complete.
        return false;
    }

    public function isRedirect()
    {
        // True so the calling application knows a redirect to the hosted payment form is needed.
        return true;
    }

    public function getRedirectUrl()
    {
        return $this->redirectUrl;
    }

    public function getRedirectMethod()
    {
        return $this->method;
    }

    public function getRedirectData()
    {
        return $this->getData();
    }
}
