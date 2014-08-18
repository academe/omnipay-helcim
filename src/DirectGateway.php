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

    const CVV2_RESPONSE_M = 'M';
    const CVV2_RESPONSE_N = 'N';
    const CVV2_RESPONSE_P = 'P';
    const CVV2_RESPONSE_S = 'S';
    const CVV2_RESPONSE_U = 'U';

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
    * For the return path from the remote Hosted Page.
    */
    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Helcim\Message\DirectCompleteRequest', $parameters);
    }

    /**
     * For handling an authorize action.
     */
    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Helcim\Message\DirectAuthorizeRequest', $parameters);
    }

    /**
    * For the return path from the remote Hosted Page.
    */
    public function completeAuthorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Helcim\Message\DirectCompleteRequest', $parameters);
    }
}
