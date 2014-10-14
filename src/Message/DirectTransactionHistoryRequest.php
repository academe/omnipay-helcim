<?php

namespace Omnipay\Helcim\Message;

use Omnipay\Common\Exception\InvalidRequestException;

/**
 * Direct accesas to the search API (aka Transaction History API).
 * This API allows a search of the logged transactions.
 *
 * Search options are:
 * - date: all transactions on a given date; OR
 * - search string: all transactions that match a given search term.
 * The search string looks at billing details, the transactionId and the orderId.
 */

class DirectTransactionHistoryRequest extends AbstractRequest
{
    protected $action = 'search';
    protected $mode = 'direct';

    /**
     * Collect the data togethee that will be sent to the API.
     * We can now include the orderId or the transactionId in the search.
     */
    public function getData()
    {
        // Get the base data.

        $data = $this->getDirectBaseData();

        // If a date has been given, then search on that.
        // The transaction history search works on date OR search string, not both.
        // The date must be in YYYYMMDD format.
        // TODO: validate the data string format.
        // TODO: accept other data types for the date and format it appropriately.

        if ($this->getTransactionDate()) {
            $data['date'] = $this->getTransactionDate();
        } elseif ($this->getSearch()) {
            $data['search'] = $this->getSearch();
        } else {
            // No valid search parameter provided.
            throw new InvalidRequestException('No search criteria provided. Require date or search string.');
        }

        return $data;
    }

    /**
     * Send the data to the remote API service.
     * This service will work with GET or POST.
     */
    public function sendData($data)
    {
        $endpoint = $this->getEndpoint();

        if ($this->getMethod() == 'GET') {
            // Send a GET request.
            // The endpoint will already have GET parameters added.

            $httpResponse = $this->httpClient->get($endpoint)->send();
        } else {
            // Send a POST request.
            // The endpoint for a POST will not have GET parameters on the URL.

            $httpResponse = $this->httpClient->post($endpoint, [], $data)->send();
        }

        // Return a SimpleXMLElement containing a list of transactions.

        return $this->createResponse($httpResponse->xml());
    }

    /**
     * Allows responses from the abstract request to be overridden.
     */
    protected function createResponse($data)
    {
        return $this->response = new DirectTransactionHistoryResponse($this, $data);
    }
}

