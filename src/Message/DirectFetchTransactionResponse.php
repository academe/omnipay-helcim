<?php

namespace Omnipay\Helcim\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;

/**
 * The result of a search for a single transaction.
 */

class DirectFetchTransactionResponse extends DirectTransactionHistoryResponse
{
    /**
     * The fetched transaction.
     */
    protected $transaction = null;

    /**
     * We will be given an array of a single XML transaction.
     * We should have exectly one; this service is used to fetch
     * just one transaction for validation.
     * FIXME: there could be more than one match - just go through them
     * to find the one with the exact transaction ID.
     */
    public function __construct($request, \SimpleXMLElement $data)
    {
        // Gather the transactions.
        parent::__construct($request, $data);

        if ($this->isSuccessful() && count($this->transactions) >= 1) {
            // Loop through the fetched transactions to find the one we
            // are looking for.
            $transaction_id = $request->getTransactionId();
            $order_id = $request->getOrderId();

            foreach($this->transactions as $transaction) {
                if (
                    ($order_id && (string)$transaction->orderId === $order_id)
                    || ($transaction_id && (string)$transaction->transactionId === $transaction_id)
                ) {
                    // Found the matching transaction.
                    $this->transaction = $transaction;
                    return;
                }
            }

            // If we have not found a matching transaction, then we have failed to fetch it.
            $this->setErrorMessage('Transaction could not be retrieved.');
        }

        return;
    }

    /**
     * The details of the transaction, as a SimpleXMLElement object.
     */
    public function getTransaction()
    {
        return $this->transaction;
    }
}

