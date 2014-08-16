<?php

namespace Omnipay\Helcim\Message;

/**
 * Helcim Abstract Request
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    /**
     * The parts used to construct the endpoint URL.
     */
    protected $endpointDevDomain = 'gatewaytest.helcim.com';
    protected $endpointProdDomain = 'gateway.helcim.com';

    protected $endpointTemplate = 'https://{domain}/hosted/';

    /**
     * The transaction type.
     * Values are: "purchase", "preauth", "capture", "refund" or "void".
     * Also "recurring", with a start data and special handling of the amount.
     * Some of these will presumably not be available to the hosted pages approach, and only
     * be useful in the server-based approach. However, the documentation leaves this unclear.
     */
    protected $type = 'purchase';

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
        $method = strtoupper($method) == 'GET' ? 'GET' : 'POST';

        return $this->setParameter('method', $method);
    }

    /**
     * The Direct base data includes the (secret) Gateway token.
     */

    protected function getDirectBaseData()
    {
        $data = array();

        $data['merchantId'] = $this->getMerchantId();
        $data['token'] = $this->getGatewayToken();
        $data['type'] = $this->type;

        return $data;
    }

    /**
     * The Hosted Page base data includes the (public) Form token.
     */

    protected function getHostedPageBaseData()
    {
        $data = array();

        $data['merchantId'] = $this->getMerchantId();
        $data['token'] = $this->getFormToken();
        $data['type'] = $this->type;

        return $data;
    }

    /**
     * Get the billing data (not including the entered card details).
     * This includes mainly optional and some mandatory details.
     */
    protected function getBillingData()
    {
        $data = array();

        $data['amount'] = $this->getAmount();

        if ($this->getDescription()) {
            $data['comments'] = $this->getDescription();
        }

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

        // TODO:
        // cardholderName (semi-documented)
        //
        // The card also has this field that does not have a use in the gateway:
        // getBillingCompany()

        if ($card = $this->getCard()) {
            // Billing details.

            // Single billing name, first and last joined.
            if ($card->getBillingFirstName() || $card->getBillingLastName()) {
                $data['billingName'] = trim($card->getBillingFirstName() . ' ' . $card->getBillingLastName());
            }

            // Single address, 1 and 2 joined.
            // The hosted form provides a single line for this field, so we join with just a space.
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

            // Note: there are no separate billing and shipping email addresses.
            if ($card->getEmail()) {
                $data['billingEmailAddress'] = $card->getEmail();
            }

            // Shipping details.

            // Single billing name, first and last joined.
            if ($card->getShippingFirstName() || $card->getShippingLastName()) {
                $data['shippingName'] = trim($card->getShippingFirstName() . ' ' . $card->getShippingLastName());
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
     * Get the card data.
     * Only used for direct API mode.
     * Start date and issue number are not supported.
     */
    protected function getCardData()
    {
        $card = array();

        if ($card = $this->getCard()) {
            if ($card->getNumber()) {
                $data['cardNumber'] = $card->getNumber();
            }

            // CHECKME: will this work if the year is '00'?
            // Not that '00' will happen for a while, but good just to be sure.

            if ($card->getExpiryMonth() && $card->getExpiryYear()) {
                $data['expiryDate'] = $card->getExpiryDate('%m%y');
            }

            $getCvv = $card->getCvv();
            if (isset($getCvv) && $getCvv != '') {
                $data['cvv'] = $getCvv;
            }

            // A customer field for Helcim, indicates how CVV will be handled.
            // The documentation lists this as mandatory for direct mode.

            if ($this->getCvvIndicator()) {
                $data['cvvIndicator'] = $this->getCvvIndicator();
            }
        }

        return $card;
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

        if ( ! isset($items)) {
            return $cart;
        }

        $item_count = $items->count();

        // Each item must be sequentially numbered.
        $item_number = 1;

        foreach($items as $item) {
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
     * The endpoint varies depending on developer mode, and whether the method is GET or POST.
     * If the method is GET, then the data needs to be added to the URL here. I've not found any
     * helper functions to construct URLs in omnipay yet, but they could be there.
     */
    public function getEndpoint()
    {
        $domain = $this->getDeveloperMode() ? $this->endpointDevDomain : $this->endpointProdDomain;

        $url = str_replace('{domain}', $domain, $this->endpointTemplate);

        if ($this->getMethod() == 'GET') {
            // For the GET method, all data needs to be added to the URL.
            // The data could be minimal - just the Merchant ID and the gateway token is enough.

            $data = $this->getData();

            $separator = strpos($url, '?') === false ? '?' : '&';
            foreach($data as $key => $value) {
                $url .= $separator;
                $url .= urlencode($key);
                $url .= '=';
                $url .= urlencode($value);

                $separator = '&';
            }
        }

        return $url;
    }
}

