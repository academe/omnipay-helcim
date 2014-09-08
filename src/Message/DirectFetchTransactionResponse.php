<?php

namespace Omnipay\Helcim\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;

/**
 * The result of a search for transactions.
 * The result will be zero or more transactions. It is not clear if a limit
 * can be set, or is defined, for the number of transactions that will be
 * returned in a single call to use of DirectSearchRequest.
 */

class DirectFetchTransactionResponse extends AbstractResponse implements RedirectResponseInterface
{
    /**
     * The fetched transaction.
     */
    protected $transaction = null;

    /**
     * The error message, if any.
     */
    protected $error = '';

    /**
     * We will be given an array of a single XML transaction.
     * We should have exectly one; this service is used to fetch
     * just one transaction for validation.
     * FIXME: there could be more than one match - just go through them
     * to find the one with the exact transaction ID.
     */
    public function __construct($request, \SimpleXMLElement $data)
    {
        if (isset($data->error)) {
            // If an error element has been found (containing a message) then
            // the transaction failed.
            $error = $data->error;
        }

        if (empty($error)) {
            $transaction_count = $data->transactions->count();

            if ($transaction_count !== 1) {
                $error = 'Transaction could not be retrieved.';
            } else {
                // Get the first and only transaction element.
                $transaction = $data->transactions->children()[0];
            }
        }

        if ( ! empty($error)) {
            // An error occurred, so mark this response as failed.
            // Being in a constructor, strange things will happen if we raise an exception.

            $this->error = $error;
        } else {
            // We have the transaction.

            // Clean up the amount return value - remove thousands separators and the currency symbol.
            $transaction->amount = preg_replace('/[^0-9.]/', '', (string)$transaction->amount);

            $this->transaction = $transaction;
        }
    }

    public function isSuccessful()
    {
        // If no transaction details are set, then we failed somewhere.
        if (empty($this->transaction)) return false;

        return true;
    }

    public function isRedirect()
    {
        return false;
    }

    public function getRedirectUrl()
    {
        return;
    }

    public function getRedirectMethod()
    {
        // Even though not used.
        return 'POST';
    }

    public function getRedirectData()
    {
        return;
    }

    /**
     * The details of the transaction, as a SimpleXMLElement object.
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * Get the transaction as an array of scalars.
     * The structure is flat, so don't need to worry about recursing,
     * tough a more generic solution may be needed for other direct services.
     * The array format will be easier to handle for storage.
     */
    public function getTransactionArray()
    {
        if (is_a($this->transaction, '\SimpleXMLElement')) {
            $array = array();

            foreach($this->transaction as $element) {
                $array[$element->getName()] = (string)$element;
            }

            return $array;
        } else {
            return null;
        }
    }

    public function getErrorMessage()
    {
        return $this->error;
    }
}

