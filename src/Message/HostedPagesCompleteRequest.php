<?php

namespace Omnipay\Helcim\Message;

use Omnipay\Common\Exception\InvalidRequestException;

/**
* Helcim Hosted Page Complete Purchase Request
* Used by both authorize and purchase.
*/
class HostedPagesCompleteRequest extends AbstractRequest
{
    protected $action = 'preauth';
    protected $mode = 'hostedpages';

    /**
     * Get the data returned by the Hosted Pages form, and check its validity.
     * Helcim does not (yet) support hashes. The data, which comes direct from the
     * end user's browser, cannot be trusted.
     * Instead, we should go direct to the service API and get the details from
     * there and merge that over the top of the user-returned data.
     * The Helcim forms support custom fields, and we may still want to capture
     * data from those fields.
     *
     * TODO: So we:
     * 1. Fetch the full transaction from the API.
     * 2. Generate a hash of important parts of the transaction.
     * 3. Generate a hash of the important parts of the submitted form (trusting
     *    no other data from the form, except for custom fields).
     * 4. Any hash discrepancy will ne flagged as an exceptrion.
     *
     * Note that some address fields may get truncated when stored or returned, so would
     * fail a hash check. We are discarding the submitted address fields anyway.
     *
     * Some transactions will leave the amount open-ended. We need to take that into account,
     * that we may not know the amount until the form is submitted. However, that amount MUST
     * match the amount stored against the transaction fetched from the API.
     *
     * In effect, we are only trying to validate the orderId so we can get he real data from
     * the service through the API. Additional hash checks are just there to make sure nothing
     * suspicious is being attempted.
     * 
     * In addition, we need to have saved the pre-generated orderId in the session,
     * so we know what orderId we are expecting.
     */

    public function getData()
    {
        // No hashes in Helcim Hosted Pages yet.
        //if (strtolower($this->httpRequest->request->get('hash')) !== $this->getHash()) {
        //    throw new InvalidRequestException('Incorrect hash');
        //}

        $order_id = 'foo';
        $this->getTransaction($order_id);

        // Return all data POSTed by the user, for now.
        return $this->httpRequest->request->all();
    }

    /**
     * Get the hash from TBC
     */
    public function getHash()
    {
        return null;
        //return md5($this->getHashSecret().$this->getApiLoginId().$this->getTransactionId().$this->getAmount());
    }

    public function sendData($data)
    {
        return $this->response = new HostedPagesCompleteResponse($this, $data);
    }
}

