<?php

namespace Omnipay\Helcim\Message;

use Omnipay\Omnipay;
use Omnipay\Common\Message\AbstractRequest as OmnipayAbstractRequest;
use Guzzle\Http\Url;
use Omnipay\Common\Exception\InvalidRequestException;

/**
 * Helcim Abstract Request
 */
abstract class AbstractRequest extends OmnipayAbstractRequest
{
    /**
     * The parts used to construct the endpoint URL.
     * The developer accounts use a different domain to the active (live) accounts.
     * In addition, the active accounts can be run in live or test mode.
     * TODO: these should be constants.
     */
    protected $endpointScheme = 'https';

    protected $endpointDevDomain = 'gatewaytest.helcim.com';
    protected $endpointProdDomain = 'gateway.helcim.com';

    //protected $endpointPathHostedPages = '/hosted/';
    //protected $endpointPathDirectHistory = '/api/';
    //protected $endpointPathDirectActions = '/';
    //protected $endpointPathJS = '/js/version{version}.js';

    /**
     * The transaction action (type).
     * Values are: "purchase", "preauth", "capture", "refund" or "void".
     * Also "recurring", with a start date and special handling of the amount.
     * The Hosted Pages mode only supports preauth and purchase.
     */
    protected $action = 'preauth';

    /**
     * The endpoint URL depends on whether the mode is Direct or Hosted Pages.
     * TODO: at this point it may be worth adding another layer of AbstractRequest to
     * differentiate between the two (at the moment) modes. This would reduct conditional
     * checking and duplication on the mode property across multiple Request classes.
     */
    protected $mode = 'direct';

    /**
     * 
     */

    public function setDeveloperMode($value)
    {
        return $this->setParameter('developerMode', $value);
    }

    public function getDeveloperMode()
    {
        return $this->getParameter('developerMode');
    }

    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setAllowZeroAmount($value)
    {
        // Evaluate to true/false
        return $this->setParameter('allowZeroAmount', ! empty($value));
    }

    public function getAllowZeroAmount()
    {
        return $this->getParameter('allowZeroAmount');
    }

    public function setCardholderName($value)
    {
        return $this->setParameter('cardholderName', $value);
    }

    public function getCardholderName()
    {
        return $this->getParameter('cardholderName');
    }

    // The cardHolder Address and PostalCode feeds into address verification services.

    public function setCardholderAddress($value)
    {
        return $this->setParameter('cardholderAddress', $value);
    }

    public function getCardholderAddress()
    {
        return $this->getParameter('cardholderAddress');
    }

    public function setCardholderPostalCode($value)
    {
        return $this->setParameter('cardholderPostalCode', $value);
    }

    public function getCardholderPostalCode()
    {
        return $this->getParameter('cardholderPostalCode');
    }

    // OmniPay has set/getToken() already. Can we use that?
    // Perhaps it can be used to accept the "gateway-token|form-token" or something?

    public function setGatewayToken($value)
    {
        return $this->setParameter('gatewayToken', $value);
    }

    public function getGatewayToken()
    {
        return $this->getParameter('gatewayToken');
    }

    public function setFormToken($value)
    {
        return $this->setParameter('formToken', $value);
    }

    public function getFormToken()
    {
        return $this->getParameter('formToken');
    }

    public function setShippingAmount($value)
    {
        return $this->setParameter('shippingAmount', $value);
    }

    public function getShippingAmount()
    {
        return $this->getParameter('shippingAmount');
    }

    public function setTaxAmount($value)
    {
        return $this->setParameter('taxAmount', $value);
    }

    public function getTaxAmount()
    {
        return $this->getParameter('taxAmount');
    }

    public function setCustomerId($value)
    {
        return $this->setParameter('customerId', $value);
    }

    public function getCustomerId()
    {
        return $this->getParameter('customerId');
    }

    public function setCvvIndicator($value)
    {
        return $this->setParameter('cvvIndicator', $value);
    }

    public function getCvvIndicator()
    {
        return $this->getParameter('cvvIndicator');
    }

    /**
     * Workaround for https://github.com/thephpleague/omnipay-common/issues/13
     */
    public function setAmount($value)
    {
        if (is_float($value) && $value === 0.0) {
            $value = '0.00';
        }

        parent::setAmount($value);
    }

    // cardoken and carF4l4 can be sent as an alternative to the
    // credit card number, expiry and CVV.
    // Only needed for Direct payment and preauth transactions.

    public function setCardToken($value)
    {
        return $this->setParameter('cardToken', $value);
    }

    public function getCardToken()
    {
        return $this->getParameter('cardToken');
    }

    // The card first four and last four digits are needed for taking payments on
    // a tokenised card.

    public function setCardF4l4($value)
    {
        return $this->setParameter('cardF4l4', $value);
    }

    public function getCardF4l4()
    {
        return $this->getParameter('cardF4l4');
    }

    /**
     * The method can be left as GET for simple payments or POST for more complex payments.
     */
    public function getMethod()
    {
        return $this->getParameter('method');
    }

    public function setMethod($method)
    {
        // Normalise to GET or POST.
        $method = (strtoupper($method) === 'GET' ? 'GET' : 'POST');

        return $this->setParameter('method', $method);
    }

    protected function getBaseData()
    {
        $data = array();

        $data['merchantId'] = $this->getMerchantId();

        // The search action is indicated to Helcim by not supplying any "type" field.

        if ($this->action != 'search') {
            $data['type'] = $this->action;
        }

        // The test parameter will indicate this is a test transaction. This flag can be
        // used in the production environment. It differs from enabling "developer mode",
        // which switches to an alternative developer environment and operates on different
        // developer merchant accounts.

        if ($this->getTestMode()) {
            $data['test'] = '1';
        }

        return $data;
    }

    /**
     * The Direct base data includes the (secret) Gateway token.
     */

    protected function getDirectBaseData()
    {
        $data = $this->getBaseData();

        $data['token'] = $this->getGatewayToken();

        return $data;
    }

    /**
     * The Hosted Page base data includes the (public) Form token.
     */

    protected function getHostedPagesBaseData()
    {
        $data = $this->getBaseData();

        $data['token'] = $this->getFormToken();

        return $data;
    }

    /**
     * Get billing address, including the name, phone number and email.
     * All parts of the address are optional at the API level, though may be
     * marked as mandatory in the hosted form created on Helcim.
     */
    protected function getBillingAddressData()
    {
        $data = array();

        if ($card = $this->getCard()) {
            // Billing address/person details.

            // Single billing name, first and last joined.

            if ($card->getBillingName()) {
                $data['billingName'] = $card->getBillingName();
            }

            // Single address, 1 and 2 joined.
            // The hosted form provides a single line for this field, so we join with just a space.
            // CHECKME: are either address parts multi-line? May need to convert to a single line.

            if ($card->getBillingAddress1() || $card->getBillingAddress2()) {
                $data['billingAddress'] = trim($card->getBillingAddress1() . ' ' . $card->getBillingAddress2());
            }

            if ($card->getBillingCity()) {
                $data['billingCity'] = $card->getBillingCity();
            }

            if ($card->getBillingState()) {
                $data['billingProvince'] = $card->getBillingState();
            }

            if ($card->getBillingPostcode()) {
                $data['billingPostalCode'] = $card->getBillingPostcode();
            }

            // This is the country spelled out in full, not the ISO3166 code.

            if ($card->getBillingCountry()) {
                $data['billingCountry'] = $card->getBillingCountry();
            }

            if ($card->getBillingPhone()) {
                $data['billingPhoneNumber'] = $card->getBillingPhone();
            }

            // Note: omnipay supports no separate billing and shipping email addresses.

            if ($card->getEmail()) {
                $data['billingEmailAddress'] = $card->getEmail();
            }
        }

        return $data;
    }

    /**
     * Get shiping address, including the name, phone number and email.
     * All parts of the address are optional at the API level.
     */
    protected function getShippingAddressData()
    {
        $data = array();

        if ($card = $this->getCard()) {
            // Single shipping name, first and last joined.

            if ($card->getShippingName()) {
                $data['shippingName'] = $card->getShippingName();
            }

            // Single address, 1 and 2 joined.
            // The hosted form provides a single line for this field, so we join with just a space.

            if ($card->getShippingAddress1() || $card->getShippingAddress2()) {
                $data['shippingAddress'] = trim($card->getShippingAddress1() . ' ' . $card->getShippingAddress2());
            }

            if ($card->getShippingCity()) {
                $data['shippingCity'] = $card->getShippingCity();
            }

            if ($card->getShippingState()) {
                $data['shippingProvince'] = $card->getShippingState();
            }

            if ($card->getShippingPostcode()) {
                $data['shippingPostalCode'] = $card->getShippingPostcode();
            }

            // This is the country spelled out in full, not the ISO3166 code.

            if ($card->getShippingCountry()) {
                $data['shippingCountry'] = $card->getShippingCountry();
            }

            if ($card->getShippingPhone()) {
                $data['shippingPhoneNumber'] = $card->getShippingPhone();
            }

            // Note: there are no separate billing and shipping email addresses in omnipay.

            if ($card->getEmail()) {
                $data['shippingEmailAddress'] = $card->getEmail();
            }
        }

        return $data;
    }

    /**
     * Get the billing data for the transaction.
     * This includes mainly optional and some mandatory details.
     */
    protected function getBillingData()
    {
        $data = array();

        $data['amount'] = $this->getAmount();

        if ($this->getAllowZeroAmount()) {
            $data['allowZeroAmount'] = '1';
        } else {
            // A zero amount without this flag set will be invalid.
            if (isset($data['amount']) && (float)$data['amount'] === 0.0) {
                throw new InvalidRequestException("The amount must not be zero without allowZeroAmount being set");
            }
        }

        if ($this->getDescription()) {
            $data['comments'] = $this->getDescription();
        }

        // There is some difference in language here between OmniPay and Helcim,
        // where the the omnipay transactionId is generated by the application
        // but the Helcim transactionId is generated by Helcim. Just be aware.

        if ($this->getTransactionId()) {
            $data['orderId'] = $this->getTransactionId();
        }

        if ($this->getShippingAmount()) {
            $data['shippingAmount'] = $this->getShippingAmount();
        }

        if ($this->getTaxAmount()) {
            $data['taxAmount'] = $this->getTaxAmount();
        }

        if ($this->getCustomerId()) {
            $data['customerId'] = $this->getCustomerId();
        }

        return $data;
    }

    /**
     * Get the card data.
     * Only used for direct API mode.
     * Start date and issue number are not supported.
     */
    protected function getCardData()
    {
        $data = array();

        // As an alternative to the card data, the tokenised card can be used.
        // This consists of the token that was captured in an earlier transaction,
        // and the outside eight digits of the card number.

        if ($this->getCardToken() && $this->getCardF4l4()) {
            $data['cardToken'] = $this->getCardToken();
            $data['cardF4l4'] = $this->getCardF4l4();
        } else {
            if ($card = $this->getCard()) {
                // Make sure all necessary parts of the card are set.
                // Checks 'number', 'expiryMonth' and 'expiryYear' and will not go
                // any further if they are not set.
                $card->validate();

                // Mandatory fields.
                $data['cardNumber'] = $card->getNumber();
                $data['expiryDate'] = $card->getExpiryDate('my');

                // Optional card fields.
                $getCvv = $card->getCvv();
                if (isset($getCvv) && $getCvv != '') {
                    $data['cvv'] = $getCvv;
                }

                // A custom field for Helcim, indicates how CVV will be handled.
                // The documentation lists this as mandatory for direct mode.

                if ($this->getCvvIndicator()) {
                    $data['cvvIndicator'] = $this->getCvvIndicator();
                }
            }
        }

        return $data;
    }

    /**
     * Get the basket/cart data.
     * A fix is needed to support all the fields we need in basket items. See:
     * https://github.com/omnipay/common/issues/11
     */
    protected function getCartData()
    {
        $cart = array();

        $items = $this->getItems();

        // If no cart (no item bag of items) has been set, then return.

        if (!isset($items)) {
            return $cart;
        }

        $item_count = $items->count();

        // Each item must be sequentially numbered.
        $item_number = 1;

        foreach ($items as $item) {
            //$item_id = $item->getParameter('sku');
            $item_description = $item->getName();
            $item_quantity = $item->getQuantity();
            $item_price = $item->getPrice();
            //$item_total = $item->getParameter('total');

            if (isset($item_id) && $item_id != '') {
                $cart['itemId' . $item_number] = $item_id;
            }

            if (isset($item_description)) {
                $cart['itemDescription' . $item_number] = $item_description;
            }

            // The Helcim API requires the quantity to be at least one (so 0.5kg is, in theory, invalid).
            // However, I believe it is the Helcim documentation that is incorrect here.
            if (isset($item_quantity)) {
                $cart['itemQuantity' . $item_number] = $item_quantity;
            }

            if (isset($item_price)) {
                $cart['itemPrice' . $item_number] = $item_price;
            }

            if (isset($item_total) && $item_total != '') {
                $cart['itemTotal' . $item_number] = $item_total;
            } else {
                // Calculate the item total, if possible, from the quantity and per-item cost.
                if (is_numeric($item_quantity) && is_numeric($item_price)) {
                    $cart['itemTotal' . $item_number] = $item_quantity * $item_price;
                }
            }

            $item_number++;
        }

        return $cart;
    }

    /**
     * Get the AVS (Address Validation Service) fields.
     */
    protected function getAvsData()
    {
        $data = array();

        if ($this->getCardholderAddress()) {
            $data['cardholderAddress'] = $this->getCardholderAddress();
        }

        if ($this->getCardholderPostalCode()) {
            $data['cardholderPostalCode'] = $this->getCardholderPostalCode();
        }

        return $data;
    }

    /**
     * Get the path for the API.
     */
    abstract public function getPath();

    /**
     * The endpoint varies depending on developer mode, and whether the method is GET or POST.
     * If the method is GET, then the data needs to be added to the URL here. I've not found any
     * helper functions to construct URLs in omnipay, but they could be there.
     * It may also be possible to leave the URL construction to Guzzle, assuming Guzzle is able
     * to add an arbitrary list of query parameters to the endpoint that may already have some
     * query parameters.
     * CHECKME: it seems that Guzzle will accept an array containing a template and substitution
     * variables for its endpoint. This would avoid us needing to construct it here.
     */
    public function getEndpoint()
    {
        $domain = $this->getDeveloperMode() ? $this->endpointDevDomain : $this->endpointProdDomain;

        $path = $this->getPath();

        // Build the URL from the parts.
        // There is a dependency on Guzzle here, which OmniPay uses, but may be a
        // bit of an assumption in the longer term.

        $url = new Url($this->endpointScheme, $domain);
        $url->setPath($path);

        if ($this->getMethod() == 'GET') {
            $url->setQuery($this->getData());
        }

        return $url;
    }
}
