<?php

namespace Omnipay\Helcim\Message;

/**
 * Helcim.JS Payment/Purchase Request.
 * This class provides the means to construct the JavaScript payment form.
 */
class JSPurchaseRequest extends AbstractRequest
{
    protected $action = 'preauth';
    protected $mode = 'js';

    protected $endpointPath = '/js/version1.js';

    /**
     *
     */
    public function getVersion()
    {
        return '1';
    }

    /**
     * Get the path for the API.
     */
    public function getPath()
    {
        // This entry point provides the URL to the JavaScript that implements the functionality

        $this->setMethod('POST');

        return $this->endpointPath;
    }
}
