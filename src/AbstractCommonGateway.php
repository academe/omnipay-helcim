<?php

namespace Omnipay\Helcim;

use Omnipay\Common\AbstractGateway;

/**
 * Helcim Gateway Driver methods common to (shared between) Hosted Page
 * and Direct modes.
 */
abstract class AbstractCommonGateway extends AbstractGateway
{
    public function getDefaultParameters()
    {
        return array(
            'merchantId' => '',
            'gatewayToken' => '',
            'method' => 'GET',
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
     * It provides access to the backend, and is always kept secret
     * from end users.
     */
    public function setGatewayToken($gateway_token)
    {
        return $this->setParameter('gatewayToken', $gateway_token);
    }

    public function getGatewayToken()
    {
        return $this->getParameter('gatewayToken');
    }

}

