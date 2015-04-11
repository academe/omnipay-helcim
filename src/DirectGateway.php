<?php

namespace Omnipay\Helcim;

/**
 * Helcim Server (direct) driver for Omnipay
 */
class DirectGateway extends AbstractCommonGateway
{
    /**
     * Constants the gateway needs or returns.
     */

    const CVV2_INDICATOR_PRESENT = '1';
    const CVV2_INDICATOR_NOT_VISIBLE = '2';
    const CVV2_INDICATOR_NOT_PRESENT = '3';
    const CVV2_INDICATOR_IGNORE = '4';

    // CHECKME: the CCV2 response codes should be (ought to be) in core Omnipay.
    // Similar for AVS responses.

    // M - Match.
    const CVV2_RESPONSE_MATCH = 'M';
    // N - No match.
    const CVV2_RESPONSE_NO_MATCH = 'N';
    // P - Not processed.
    const CVV2_RESPONSE_NOT_PROCESSED = 'P';
    // S - Issuer indicates CCV2 should be present, but merchant has not presetnted it.
    const CVV2_RESPONSE_NOT_PRESENTED = 'S';
    // Issuer not certified.
    const CVV2_RESPONSE_NOT_CERTIFIED = 'U';

    // AVS reponse codes.
    // These could possibly be intermationally standardised, so may be better
    // in OmniPay core or shared in some other way.

    public static $avs_response_codes = array(
        'A' => 'Address (Street) matches, Zip does not.',
        'B' => 'Street address match, Postal code in wrong format. (international issuer)',
        'C' => 'Street address and postal code in wrong formats',
        'D' => 'Street address and postal code match (international issuer)',
        'E' => 'AVS error',
        'F' => 'Address does compare and five-digit ZIP code does compare (UK only).',
        'G' => 'Card issued by a non-US issuer that does not participate in the AVS System',
        'I' => 'Address information not verified by international issuer.',
        'M' => 'Street Address and Postal code match (international issuer)',
        'N' => 'No Match on Address (Street) or Zip',
        'P' => 'Postal codes match, Street address not verified due to incompatible formats.',
        'R' => 'Retry, System unavailable or Timed out',
        'S' => 'Service not supported by issuer',
        'U' => 'Address information is unavailable (domestic issuer)',
        'W' => '9 digit Zip matches, Address (Street) does not',
        'X' => 'Exact AVS Match Y = Address (Street) and 5 digit Zip match',
        'Z' => '5 digit Zip matches, Address (Street) does not',
    );

    public function getName()
    {
        return 'Helcim Direct';
    }

    /**
     * For handling a purchase.
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Helcim\Message\DirectPurchaseRequest', $parameters);
    }

    /**
     * For handling an authorize action.
     */
    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Helcim\Message\DirectAuthorizeRequest', $parameters);
    }

    /**
     * For handling a capture action.
     */
    public function capture(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Helcim\Message\DirectCaptureRequest', $parameters);
    }

    /**
     * For handling a void action.
     */
    public function void(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Helcim\Message\DirectVoidRequest', $parameters);
    }

    /**
     * For handling a refund action.
     */
    public function refund(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Helcim\Message\DirectRefundRequest', $parameters);
    }

    /**
     * For handling a createCard action.
     */
    public function createCard(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Helcim\Message\DirectCreateCardRequest', $parameters);
    }

    /**
     * To fetch a single transaction.
     * Fetch by transactionId or orderId. Both will be unique.
     */
    public function fetchTransaction(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Helcim\Message\DirectFetchTransactionRequest', $parameters);
    }

    /**
     * To search through the past transaction history.
     */
    public function transactionHistory(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Helcim\Message\DirectTransactionHistoryRequest', $parameters);
    }
}
