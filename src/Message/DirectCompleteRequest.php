<?php

namespace Omnipay\Helcim\Message;

use Omnipay\Common\Exception\InvalidResponseException;

/**
 * Generic Complete Request for the Direct mode.
 * This may need to be split into more specific requests later.
 * CHECKME: the response may be direct data, and may be a redirect to 3D Secure - how is that
 * handled? Maybe we have a redirect CompleteRequest and a non-redirect version?
 * omnipay kind of looks simple on the surface, but the lack of a visual overview makes
 * develovoping for it an enormous pain. There are just no clues as to what processes expect what
 * interface implementations to be passed to them. Every driver does it slightly differently to
 * meet their specific needs, so reverse engineering becomes a guessing game of what the reasons
 * are behind what each driver is trying to do.
 *
 * TODO: this needs to implement RedirectResponseInterface or ResponseInterface
 */
class DirectCompleteRequest extends AbstractRequest
{
    public function getData()
    {
    }

    public function sendData($data)
    {
    }
}
