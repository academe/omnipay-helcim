<?php

namespace Omnipay\Helcim;

/**
 * Helcim JS Version 1 Class 
 * Version 1 was released 2014-10
 */
class JSGateway extends AbstractCommonGateway
{
    public function getName()
    {
        return 'Helcim.JS';
    }

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
