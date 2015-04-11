<?php

namespace Omnipay\Helcim\Message;

use Omnipay\Common\Message\AbstractResponse;
//use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Common\Message\RequestInterface;
use IteratorAggregate;
use Countable;
use SimpleXMLElement;

/**
 * The result of a search for transactions.
 * The result will be zero or more transactions. It is not clear if a limit
 * can be set, or is defined, for the number of transactions that will be
 * returned in a single call to use of DirectSearchRequest.
 */

class DirectTransactionHistoryResponse extends DirectFetchTransactionResponse implements IteratorAggregate, Countable
{
    /**
     * The fetched transactions.
     */
    protected $transactions = array();

    /**
     * We will be given an array of a XML transactions.
     */
    public function __construct($request, SimpleXMLElement $data)
    {
        $this->request = $request;

        // Check if there was an API error first.
        if (isset($data->error)) {
            $this->setErrorMessage((string)$data->error);
        }

        if ($this->isSuccessful()) {
            $transaction_count = $data->transactions->count();

            if ($transaction_count > 0) {
                // Add each transaction, as an object, to the list.

                foreach ($data->transactions->children() as $transaction) {
                    $this->transactions[] = new DirectFetchTransactionResponse($this->request, $transaction);
                }
            }
        }
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->transactions);
    }

    /**
     * Count the number of transactions
     */
    public function count()
    {
        return count($this->transactions);
    }
}
