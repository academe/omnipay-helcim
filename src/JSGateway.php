<?php

namespace Omnipay\Helcim;

/**
 * Helcim JS Version 1 (2014-10) Class
 */
class JSGateway extends AbstractCommonGateway
{
    public function getName()
    {
        return 'Helcim.JS';
    }

    /**
     * The API version.
     * Furture versions will have different names, e.g. JS2Gateway
     */
    const $version = '1';

    /**
     * For handling a purchase.
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Helcim\Message\JSPurchaseRequest', $parameters);
    }

    /**
    * For the return path from the remote Hosted Page.
    */
    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Helcim\Message\JSCompletePurchaseRequest', $parameters);
    }
}
