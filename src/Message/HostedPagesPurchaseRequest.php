<?php

namespace Omnipay\Helcim\Message;

/**
 * Helcim Hosted Pages Purchase Request
 */
class HostedPagesPurchaseRequest extends HostedPagesAuthorizeRequest
{
    protected $action = 'purchase';
}
