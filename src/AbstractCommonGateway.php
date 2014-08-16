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
}

