<?php

namespace Omnipay\Helcim\Message;

/**
 * Helcim JS Purchase Request
 */
class JSCompleteAuthorizeRequest extends HostedPagesCompletePurchaseRequest
{
    protected $action = 'purchase';
    protected $mode = 'js';
}
