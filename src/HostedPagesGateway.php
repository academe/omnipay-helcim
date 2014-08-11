<?php

namespace Omnipay\Helcim;

//use Omnipay\Helcim\Message\XXXAuthorizeRequest;
//use Omnipay\Helcim\Message\XXXPurchaseRequest;
//use Omnipay\Helcim\Message\CaptureRequest;
use Omnipay\Common\AbstractGateway;

/**
 * Helcim Hosted Pages Class
 */
class HostedPagesGateway extends AbstractGateway
{
    public function getName()
    {
        return 'Helcim Hosted Pages';
    }

    public function getDefaultParameters()
    {
        return array(
            'merchantId' => '',
            'gatewayToken' => '',
            'method' => 'GET',
            'type' => 'purchase',
            'testMode' => false,
            'developerMode' => false,
            'shippingAmount' => 0,
            'taxAmount' => 0,
        );
    }

    /**
     * The Merchant ID is always needed.
     */
    public function setMerchantId($merchant_id)
    {
        if ( ! is_numeric($merchant_id)) {
            throw new InvalidRequestException('Merchant ID must be numeric');
        }

        return $this->setParameter('merchantId', $merchant_id);
    }

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    /**
     * The Gateway Token is always needed.
     */
    public function setGatewayToken($gateway_token)
    {
        return $this->setParameter('gatewayToken', $gateway_token);
    }

    public function getGatewayToken()
    {
        return $this->getParameter('gatewayToken');
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
     * The method used to redirect.
     * GET or POST.
     */
    public function setMethod($method)
    {
        return $this->setParameter('method', $method);
    }

    public function getMethod()
    {
        return $this->getParameter('method');
    }

    /**
     * The different types of request.
     */

    /**
     * For handling a purchase.
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Helcim\Message\HostedPagesPurchaseRequest', $parameters);
    }

    /**
    * For the return URL.
    */
    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Helcim\Message\HostedPagesCompletePurchaseRequest', $parameters);
    }

    /**
     * The developer mode affects the endpoint URL.
     */
    public function setDeveloperMode($value)
    {
        return $this->setParameter('developerMode', $value);
    }

    public function getDeveloperMode()
    {
        return $this->getParameter('developerMode');
    }
}

