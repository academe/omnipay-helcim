<?php

namespace Omnipay\Helcim\Message;

use Omnipay\Common\Message\RequestInterface;

/**
 * A single historic transaction pulled back from Helcim History.
 */
class DirectFetchTransactionResponse extends AbstractResponse
{
    /**
     * The fetched transaction.
     */
    protected $transaction;

    /**
     * The error message, if any.
     */
    protected $error_message = '';

    /**
     * Accept a single transaction as an XML document, an XML error
     * or false if there are no transactions that can be passed in.
     */
    public function __construct(RequestInterface $request, $data)
    {
        if ($data === false) {
            // No matches just warrants an error message. It is not
            // an exception at this point.
            $this->setErrorMessage('No match found.');
        } elseif (isset($data->error_message)) {
            // Check if this is an XML error.
            $this->setErrorMessage((string)$data->error_message);
        }

        // Transfer all transaction elements (fields) to the data property.
        if ($this->isSuccessful() && is_a($data, 'SimpleXMLElement')) {
            // The amount field needs special handling, to remove the currency
            // and thousands separator symbols that the API adds in.

            // Save the formatted amount for reference.
            $data->display_amount = $data->amount;

            // Remove everything but the decimal number.
            $data->amount = preg_replace('/[^0-9.]/', '', (string)$data->amount);

            foreach ($data as $element) {
                $element_name = $element->getName();

                // Revisit the card return. OmniPay is not geared up for translating fields
                // on responses from the gateway, except for a very few to just determine the
                // status.

                /*
                if ($element_name == 'cardNumber') $element_name = 'number';

                if ($element_name == 'billingProvince') $element_name = 'billingState';
                if ($element_name == 'billingPostalCode') $element_name = 'billingPostcode';
                if ($element_name == 'billingPhoneNumber') $element_name = 'billingPhone';
                if ($element_name == 'billingEmailAddress') $element_name = 'billingEmail';

                if ($element_name == 'shippingProvince') $element_name = 'shippingState';
                if ($element_name == 'shippingPostalCode') $element_name = 'shippingPostcode';
                if ($element_name == 'shippingPhoneNumber') $element_name = 'shippingPhone';
                if ($element_name == 'shippingEmailAddress') $element_name = 'shippingEmail';
                */

                $this->data[$element_name] = (string)$element;
            }

            // Recreate a card object, for convenience.
            /*
            $card = new \Omnipay\Common\CreditCard($this->data);
            $this->data['card'] = $card;
            */
        }
    }

    /**
     * On any failure, we will log an error message, so use the message as a proxy.
     */
    public function isSuccessful()
    {
        return (empty($this->error_message));
    }

    /**
     * The details of the transaction, as an array.
     */
    public function getTransaction()
    {
        return $this->data;
    }

    public function setErrorMessage($error_message)
    {
        $this->error_message = $error_message;
    }

    public function getErrorMessage()
    {
        return $this->error_message;
    }

    /*
    public function getCard()
    {
        return isset($this->data['card']) ? $this->data['card'] : null;
    }
    */
}
