<?php

namespace Omnipay\Helcim\Message;

class HostedPagesCompletePurchaseRequest extends HostedPagesCompleteAuthorizeRequest
{
    protected $action = 'purchase';
}
