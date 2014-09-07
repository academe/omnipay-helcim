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

        // The date is mandatory.
        // If not supplied, then use teh current day.

        $data['date'] = $this->getTransactionDate();

        if (empty($data['date'])) {
            // YYYYMMDD
            $data['date'] = date('Ymd');
        }

        return $data;
    }

    /**
     * Send the data to the remote API service.
     * We need to go through the recieved transactions and find the one with
     * the orderId or transactionId that we want to get.
     */
    public function sendData($data)
    {
        $order_id = $this->getOrderId();

        // Send the POST to the gateway.
        $httpResponse = $this
            ->httpClient
            ->get($this->getEndpoint(), null, $data)
            ->send();

        $transactions = $httpResponse->xml();

        $transaction = null;

        if (isset($transactions->transactions->transaction)) {
            foreach($transactions->transactions->transaction as $transaction_record) {
                if ((string)$transaction_record->orderId === $order_id) {
                    $transaction = $transaction_record;
                    break;
                }
            }
        }

        // We return a SimpleXMLElement here.
        // It is not clear what we are supposed to return, or how it gets converted into
        // a more standard format for the application. Something will need to pull all the
        // XML object data out and put it into something else. But what and where...?
        // Other things to consider are special handling of some fields, such as the
        // conversion of the amount from a formatted string - with currency symbol and possibly
        // thousands separator charactrs - to a numeric value.

        return new DirectFetchTransactionResponse($this, $transaction);
    }
}

