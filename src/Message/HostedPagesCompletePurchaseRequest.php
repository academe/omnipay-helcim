<?php

namespace Omnipay\Helcim\Message;

use Omnipay\Common\Exception\InvalidRequestException;

/**
* Helcim Hosted Page Complete Purchase Request
*/
class HostedPagesCompletePurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        // No hashes in Helcim Hosted Pages yet.
        //if (strtolower($this->httpRequest->request->get('hash')) !== $this->getHash()) {
        //    throw new InvalidRequestException('Incorrect hash');
        //}

        return $this->httpRequest->request->all();
    }

    public function getHash()
    {
        return null;
        //return md5($this->getHashSecret().$this->getApiLoginId().$this->getTransactionId().$this->getAmount());
    }

    public function sendData($data)
    {
        return $this->response = new HostedPagesCompletePurchaseResponse($this, $data);
    }
}

