<?php

namespace Omnipay\Helcim\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\RequestInterface;

/**
 * Direct accesas to the search API (aka Transaction History API) to fetch a
 * a single transaction.
 * This service may return multiple transactions if more than one happen to
 * contain the matching transactionId or orderId, but will be filtered down
 * to one when the result is processed.
 */

class DirectFetchTransactionRequest extends AbstractRequest implements RequestInterface
{
    protected $action = 'search';
    protected $mode = 'direct';

    protected $endpointPathHistory = '/api/';

    /**
     * Collect the data together that will be sent to the API.
     * We can now include the orderId or the transactionId in the search.
     * If either unique ID is provided, then move it to the general search string
     * and pass the remaining handling to the parent.
     */
    public function getData()
    {
        // Move the transaction ID or transaction reference into the search
        // string, if provided.

        if ($this->getTransactionId()) {
            $this->setSearch($this->getTransactionId());
        } elseif ($this->getTransactionReference()) {
            $this->setSearch($this->getTransactionReference());
        } else {
            // No valid search parameter provided.
            throw new InvalidRequestException(
                'Missing transactionId or transactionReference; needed to fetch a single transaction.'
            );
        }

        // Get the base data.

        $data = $this->getDirectBaseData();

        // Add in the search fields.

        $data = $this->getSearchData($data);

        return $data;
    }

    /**
     * Get the path for the API.
     * The "history" API is under the path /api/.
     */
    public function getPath()
    {
        return $this->endpointPathHistory;
    }


    /**
     *
     */
    public function getSearchData($data = array())
    {
        // If a date has been given, then search on that.
        // The transaction history search works on date OR search string, not both.
        // The date must be in YYYYMMDD format.
        // TODO: validate the data string format.
        // TODO: accept other data types for the date and format it appropriately.
        // TODO: The search string must be at least four characters long.

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
     * The sendData() method will return a list of matching transactions.
     * We hope it will be a lost of one, but if it's not, then we need to
     * go through them and choose the one that matches the correct reference.
     */
    protected function createResponse($data)
    {
        $found = false;

        if (isset($data->error)) {
            // An XML error was returned.
            $found = $data;
        } else {
            // No errors, so see what we got.

            if (isset($data->transactions->transaction)) {
                // The number of transactions returned.
                $count = $data->transactions->transaction->count();

                $transaction_id = $this->getTransactionId();
                $transaction_reference = $this->getTransactionReference();

                // Loop for each transaction.
                foreach ($data->transactions->transaction as $transaction) {
                    if (
                        ($transaction_id && (string)$transaction->orderId === $transaction_id)
                        || ($transaction_reference && (string)$transaction->transactionId === $transaction_reference)
                    ) {
                        // Found the matching transaction.
                        $found = $transaction;
                        break;
                    }
                }
            }
        }

        return $this->response = new DirectFetchTransactionResponse($this, $found);
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
     * The transaction date is used when fetching a transaction from the API.
     */
    public function setTransactionDate($value)
    {
        return $this->setParameter('transactionDate', $value);
    }

    public function getTransactionDate()
    {
        return $this->getParameter('transactionDate');
    }

    /**
     * The transaction search string is used when fetching a transaction from the API.
     */
    public function setSearch($value)
    {
        return $this->setParameter('search', $value);
    }

    public function getSearch()
    {
        return $this->getParameter('search');
    }
}
