<?php

namespace Omnipay\Helcim\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;

/**
 * The result of a search for transactions.
 * The result will be zero or more transactions. It is not clear if a limit
 * can be set, or is defined, for the number of transactions that will be
 * returned in a single call to use of DirectSearchRequest.
 */

class DirectFetchTransactionResponse extends AbstractResponse implements RedirectResponseInterface
{
    public function isSuccessful()
    {
        // TODO: check the XML response.
        return true;
    }

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

