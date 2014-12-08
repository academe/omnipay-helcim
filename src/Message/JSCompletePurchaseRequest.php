<?php

namespace Omnipay\Helcim\Message;

/**
 * Helcim JS Purchase Request
 */
class JSCompletePurchaseRequest extends HostedPagesCompletePurchaseRequest
{
    protected $action = 'purchase';
    protected $mode = 'js';
}
