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

class DirectTransactionHistoryRequest extends DirectFetchTransactionRequest
{
    protected $action = 'search';
    protected $mode = 'direct';

    /**
     * Collect the data together that will be sent to the API.
     * We can now include the orderId or the transactionId in the search.
     */
    public function getData()
    {
        // Get the base data.

        $data = $this->getDirectBaseData();

        // Add in the search fields.

        $data = $this->getSearchData($data);

        return $data;
    }

    /**
     * Allows responses from the abstract request to be overridden.
     */
    protected function createResponse($data)
    {
        return $this->response = new DirectTransactionHistoryResponse($this, $data);
    }
}
