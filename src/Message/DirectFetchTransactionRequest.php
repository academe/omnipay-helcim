<?php

namespace Omnipay\Helcim\Message;

/**
 * Direct accesas to the search API.
 * This API allows a [very limited] search of the current transactions.
 * Search options are:
 * - date: all transactions on a given date.
 * - search string: all transactions that match a given search term.
 * The search string looks at billing details only. A request has been put in
 * to allow searching (i.e. selecting) by transaction ID or order ID.
 */

class DirectFetchTransactionRequest extends AbstractRequest
{
    protected $action = 'search';
    protected $mode = 'direct';

    /**
     * Collect the data togethee that will be sent to the API.
     * FIXME: we can now include the orderId or the transactionId in the search,
     * and neither of those are date-limted. We still need to consider that more
     * than one match may be returned.
     */
    public function getData()
    {
        // Get the base data.

        $data = $this->getDirectBaseData();

        // TODO: check various fields for a unique ID and use that.
        $data['search'] = $this->getOrderId();

        return $data;
    }

    /**
     * TODO: move this to AbstractRequest.
     * Check first if all the direct transaction types expect an XML response.
     * Send the data to the remote API service.
     * We need to go through the recieved transactions and find the one with
     * the orderId or transactionId that we want to get.
     */
    public function sendData($data)
    {
        $method = $this->getMethod();
        $endpoint = $this->getEndpoint();

        if ($method == 'GET') {
            // The endpoint will already have GET parameters added.
            $httpResponse = $this->httpClient->get($endpoint)->send();
        } else {
            // Send a POST request.
            // TODO
        }

        // Return a SimpleXMLElement containing a list of transactions

        return $this->createResponse($httpResponse->xml());
    }

    /**
     * Allows responses from the abstract request to be overridden.
     */
    protected function createResponse($data)
    {
        return $this->response = new DirectFetchTransactionResponse($this, $data);
    }
}

