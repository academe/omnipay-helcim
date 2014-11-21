<?php

namespace Omnipay\Helcim\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Helcim Hosted Pages Authorize Response.
 * This is a "fake" rssponse - it does not come from the remote payment gateway, but is generated
 * locally. It contains the next action, with may be a redirect to the hosted payment page.
 * This response is used by both authorize and purchase.
 */
class HostedPagesResponse extends AbstractResponse implements RedirectResponseInterface
{
    // CHECKME: is this used?
    protected $redirectUrl;

    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        $this->data = $data;
    }

    public function isSuccessful()
    {
        // False so the calling applicaton knows that the transaction
        // is not yet complete (until after the redirect).
        return false;
    }

    public function isRedirect()
    {
        // True so the calling application knows a redirect
        // to the hosted payment form is needed.
        return true;
    }

    public function getRedirectUrl()
    {
        return $this->request->getEndpoint();
    }

    public function getRedirectMethod()
    {
        return $this->request->getMethod();
    }

    public function getRedirectData()
    {
        return $this->getData();
    }
}
