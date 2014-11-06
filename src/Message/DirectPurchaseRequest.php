<?php

namespace Omnipay\Helcim\Message;

/**
* Helcim Direct Direct Purchase Request
*/
class DirectPurchaseRequest extends DirectAuthorizeRequest
{
    protected $action = 'purchase';
}
