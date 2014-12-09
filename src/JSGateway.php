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
    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Helcim\Message\JSAuthorizeRequest', $parameters);
    }

    /**
    * For the return path from the remote Hosted Page.
    */
    public function completeAuthorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Helcim\Message\JSAuthorizePurchaseRequest', $parameters);
    }
}
