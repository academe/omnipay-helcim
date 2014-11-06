<?php

namespace Omnipay\Helcim\Message;

/**
* Helcim Direct Direct Create Card Request
*/
class DirectCreateCardRequest extends DirectAuthorizeRequest
{
    /**
     * Collect the data together to sent to the Gateway.
     * It is just like any normal authorize, except the amount is
     * zero and the allowZeroAmount flag is set to allow this.
     */
    public function getData()
    {
        $this->setAllowZeroAmount(true);
        $this->setAmount('0.00');

        return parent::getData();
    }
}
