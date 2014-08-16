<?php

namespace Omnipay\Helcim;

/**
 * Helcim Server (direct) driver for Omnipay
 */
class ServerGateway extends AbstractCommonGateway
{
    public function getName()
    {
        return 'Helcim Server';
    }

    /**
     * For handling a purchase.
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Helcim\Message\ServerPurchaseRequest', $parameters);
    }

    /**
    * For the return path from the remote Hosted Page.
    */
    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Helcim\Message\ServerCompleteRequest', $parameters);
    }

    /**
     * For handling an authorize action.
     */
    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Helcim\Message\ServerAuthorizeRequest', $parameters);
    }

    /**
    * For the return path from the remote Hosted Page.
    */
    public function completeAuthorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Helcim\Message\ServerCompleteRequest', $parameters);
    }
}
