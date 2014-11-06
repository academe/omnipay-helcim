<?php

namespace Omnipay\Helcim;

/**
 * Helcim Hosted Pages Class
 */
class HostedPagesGateway extends AbstractCommonGateway
{
    public function getName()
    {
        return 'Helcim Hosted Pages';
    }

    public function getDefaultParameters()
    {
        // Merge the formToken with the common default values.
        return array_merge(
            parent::getDefaultParameters(),
            array(
                'formToken' => '',
            )
        );
    }

    /**
     * The Form Token is always needed.
     * It identifies which form is going to be presented to the user.
     * The form ID will be public..
     */
    public function setFormToken($gateway_token)
    {
        return $this->setParameter('formToken', $gateway_token);
    }

    public function getFormToken()
    {
        return $this->getParameter('formToken');
    }

    /**
     * Entry points to the different types of request.
     */

    /**
     * For handling a purchase.
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Helcim\Message\HostedPagesPurchaseRequest', $parameters);
    }

    /**
    * For the return path from the remote Hosted Page.
    */
    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Helcim\Message\HostedPagesCompletePurchaseRequest', $parameters);
    }

    /**
     * For handling an authorise action.
     */
    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Helcim\Message\HostedPagesAuthorizeRequest', $parameters);
    }

    /**
    * For the return path from the remote Hosted Page.
    */
    public function completeAuthorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Helcim\Message\HostedPagesCompleteAuthorizeRequest', $parameters);
    }
}
