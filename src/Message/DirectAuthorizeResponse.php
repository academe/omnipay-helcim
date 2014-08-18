<?php

namespace Omnipay\Helcim\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;

/**
* Helcim Direct Direct Authorize Response
*/
class DirectAuthorizeResponse extends AbstractResponse implements RedirectResponseInterface
{
    /**
     * Returns true if the transaction is complete and authorised, and there are no
     * further steps, such as redirects to complete 3DAuth.
     */
    public function isSuccessful()
    {
        //return true;
    }

    /**
     * The response is never a redirect. No 3DAuth or similar service is supported
     * by Helcim at this time.
     */
    public function isRedirect()
    {
        return false;
    }

    public function getRedirectUrl()
    {
        return;
    }

    public function getRedirectMethod()
    {
        // Even though not used.
        return 'POST';
    }

    public function getRedirectData()
    {
        return;
    }
}

