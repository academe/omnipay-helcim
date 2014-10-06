<?php

namespace Omnipay\Helcim\Message;

use Omnipay\Common\Exception\InvalidRequestException;

/**
 * Direct accesas to the search API (aka Transaction History API) to fetch a
 * a single transaction.
 * This service may return multiple transactions if more than one happen to
 * contain the matching transactionId or orderId, but will be filtered down
 * to one when the result is processed.
 */

class DirectFetchTransactionRequest extends DirectTransactionHistoryRequest
{
    /**
     * Collect the data together that will be sent to the API.
     * We can now include the orderId or the transactionId in the search.
     * If either unique ID is provided, then move it to the general search string
     * and pass the remaining handling to the parent.
     */
    public function getData()
    {
        if ($this->getOrderId()) {
            $this->setSearch($this->getOrderId());
        } elseif ($this->getTransactionId()) {
            $this->setSearch($this->getTransactionId());
        } else {
            // No valid search parameter provided.
            throw new InvalidRequestException('Missing orderId or transactionId; needed to fetch a single transaction.');
        }

        return parent::getData();
    }

    /**
     * Allows responses from the abstract request to be overridden.
     */
    protected function createResponse($data)
    {
        return $this->response = new DirectFetchTransactionResponse($this, $data);
    }
}

