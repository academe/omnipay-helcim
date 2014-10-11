<?php

namespace Omnipay\Helcim\Message;

//use Omnipay\Common\Exception\InvalidRequestException;

/**
 * Direct accesas to the capture action, to capture funds that
 * have already been authorized.
 */

class DirectVoidRequest extends DirectCaptureRequest
{
    protected $action = 'void';
    protected $mode = 'direct';
}
