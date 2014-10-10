<?php

namespace Omnipay\Helcim\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Common\Message\RequestInterface;

/**
* Helcim Direct Direct Authorize Response
*/
class DirectAuthorizeResponse extends AbstractResponse implements ResponseInterface
{
    /**
     * Returns true if the transaction is complete and authorised, and there are no
     * further steps, such as redirects to complete 3DAuth.
     */
    public function isSuccessful()
    {
        //return true;
    }
}

