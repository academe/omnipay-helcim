<?php

namespace Omnipay\Helcim\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Common\Message\RequestInterface;

/**
 * The result of a search for transactions.
 * The result will be zero or more transactions. It is not clear if a limit
 * can be set, or is defined, for the number of transactions that will be
 * returned in a single call to use of DirectSearchRequest.
 */

class DirectTransactionHistoryResponse extends AbstractResponse implements ResponseInterface
{
    /**
     * The fetched transactions.
     */
    protected $transactions = null;

    /**
     * The error message, if any.
     */
    protected $error = '';

    /**
     * On any failure, we will log an error message, so use the message as a proxy.
     */
    public function isSuccessful()
    {
        return (empty($this->error));
    }

    /**
     * We will be given an array of a XML transactions.
     */
    public function __construct($request, \SimpleXMLElement $data)
    {
        if (isset($data->error)) {
            // If an error element has been found (containing a message) then
            // the transaction failed.
            $this->setErrorMessage($data->error);
        }

        if ($this->isSuccessful()) {
            $transaction_count = $data->transactions->count();

            if ($transaction_count > 0) {
                // Get the transaction elements.
                $this->transactions = $data->transactions->children();

                // Clean up the pre-formatted "amount" fields - remove thousands separators
                // and the currency symbol.
                foreach($this->transactions as $transaction) {
                    // Save the formatted amount for reference.
                    $transaction->display_amount = $transaction->amount;

                    // Remove everything but the decimal number.
                    $transaction->amount = preg_replace('/[^0-9.]/', '', (string)$transaction->amount);
                }
            }
        }
    }

    /**
     * Get a transaction as an array of scalars.
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

    public function setErrorMessage($error)
    {
        $this->error = $error;
    }

    public function getErrorMessage()
    {
        return $this->error;
    }
}

