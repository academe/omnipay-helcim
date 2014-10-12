<?php

namespace Omnipay\Helcim\Message;

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
     * We should have exactly one; this service is used to fetch
     * just one transaction for validation.
     * FIXME: there could be more than one match - just go through them
     * to find the one with the exact transactionId or transactionReference.
     */
    public function __construct($request, \SimpleXMLElement $data)
    {
        // Gather the transactions.
        parent::__construct($request, $data);

        if ($this->isSuccessful() && count($this->transactions) >= 1) {
            // Loop through the fetched transactions to find the one we
            // are looking for.
            $transaction_id = $request->getTransactionId();
            $transaction_reference = $request->getTransactionReference();

            foreach($this->transactions as $transaction) {
                if (
                    ($transaction_id && (string)$transaction->orderId === $transaction_id)
                    || ($transaction_reference && (string)$transaction->transactionId === $transaction_reference)
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
     * No, we don't want to spit out XML. See Issue #11
     */
    public function getTransaction()
    {
        return $this->transaction;
    }
}

