<?php

namespace Omnipay\Helcim\Message;

use Omnipay\Common\Message\AbstractResponse as OmnipayAbstractResponse;

/**
* Access to some common fields returned by most of the responses.
*/
abstract class AbstractResponse extends OmnipayAbstractResponse
{
    /**
     * Get the first and last four digits from the card.
     * These will be returned here, but with asterisks that need to be removed.
     * e.g. "4242****4242"
     */
    public function getCardF4l4()
    {
        return isset($this->data['cardNumber']) ? preg_replace('/[^0-9]/', '', $this->data['cardNumber']) : null;
    }

    /**
     * The displayable card number.
     */
    public function getCardNumber()
    {
        return isset($this->data['cardNumber']) ? $this->data['cardNumber'] : null;
    }

    /**
     * The Helcim Order ID is ultimately application generated. However, if the application
     * does not provide one, then Helcim will make one for you.
     */
    public function getTransactionId()
    {
        return isset($this->data['orderId']) ? $this->data['orderId'] : null;
    }

    public function getTransactionReference()
    {
        return isset($this->data['transactionId']) ? $this->data['transactionId'] : null;
    }

    /**
     * The cardholder name is returned where given by the customer.
     */
    public function getCardholderName()
    {
        return isset($this->data['cardholderName']) ? $this->data['cardholderName'] : null;
    }

    /**
     * The amount can sometimes be changed by the customer (e.g. for the Hosted Pages mode).
     */
    public function getAmount()
    {
        return isset($this->data['amount']) ? $this->data['amount'] : null;
    }

    /**
     * The date the transaction was logged, Helcim Locale.
     */
    public function getDate()
    {
        return isset($this->data['date']) ? $this->data['date'] : null;
    }

    /**
     * The time the transaction was logged, Helcim Locale.
     */
    public function getTime()
    {
        return isset($this->data['time']) ? $this->data['time'] : null;
    }

    /**
     * The card expiry date, in MMYY format.
     * TODO: provide getMonth() and getYear() to split this up.
     */
    public function getExpiryDate()
    {
        return isset($this->data['expiryDate']) ? $this->data['expiryDate'] : null;
    }

    /**
     * The type of card used.
     * CHECKME: is this translatable into a more standard set of values, e.g. "V" for "Visa"?
     */
    public function getCardType()
    {
        return isset($this->data['cardType']) ? $this->data['cardType'] : null;
    }

    // CHECKME: the "code" is the approval code?
    public function getCode()
    {
        return isset($this->data['approvalCode']) ? $this->data['approvalCode'] : null;
    }

    /**
     * AVS response raw code.
     */
    public function getAvsResponse()
    {
        return isset($this->data['avsResponse']) ? $this->data['avsResponse'] : null;
    }

    /**
     * CVV response raw code.
     */
    public function getCvvResponse()
    {
        return isset($this->data['cvvResponse']) ? $this->data['cvvResponse'] : null;
    }
}

