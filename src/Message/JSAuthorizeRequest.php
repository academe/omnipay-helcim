<?php

namespace Omnipay\Helcim\Message;

/**
 * Helcim.JS Payment/Purchase Request.
 * This class provides the means to construct the JavaScript payment form.
 */
class JSAuthorizeRequest extends AbstractRequest
{
    protected $action = 'preauth';
    protected $mode = 'js';

    // The same endpoint and JS source is used for both productrion and dev
    // instances, according to the documentation at least. I'm not sure this
    // is correct, as the dev mode is supposed to post to the dev domain, and
    // cross-domain security in the browser should stop that from happening.

    protected $endpointPath = '/js/version1.js';
    protected $endpointDevDomain = 'gateway.helcim.com';

    // Some helpful constants.
    // CHECKME: should these perhaps be moved to the gateway class?

    const HTML_FORM_NAME = 'helcimForm';
    const HTML_FORM_ID = 'helcimForm';

    const HTML_RESULTS_NAME = 'helcimResults';
    const HTML_RESULTS_ID = 'helcimResults';

    const HTML_BUTTON_NAME = 'buttonProcess';
    const HTML_BUTTON_ID = 'buttonProcess';

    const HTML_TEMPLATE_HIDDEN_FIELD = '<input type="hidden" name="{name}" value="{value}" />';

    /**
     * The API version.
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
        // This entry point provides the URL to the JavaScript that implements the
        // authorization functionality.

        // The endpoint should never inherit GET parameters.
        $this->setMethod('POST');

        return $this->endpointPath;
    }

    /**
     * Helper function to get the common hidden form fields.
     * Any additional optional fields can be set as hidden.
     * TODO: the ability to pull out additional fields as hidden would be useful.
     */
    public function getHiddenFormFields()
    {
        $html = array();

        // The merchant ID is mandatory.

        $html[] = replace(
            array('{name}', '{value}'),
            array('merchantId', $this->getMerchantId()),
            $this::HTML_TEMPLATE_HIDDEN_FIELD
        );

        // The dev mode flag is optional.

        if ($this->getDeveloperMode()) {
            $html[] = replace(
                array('{name}', '{value}'),
                array('dev', '1'),
                $this::HTML_TEMPLATE_HIDDEN_FIELD
            );
        }

        // The test mode flag is optional.

        if ($this->getTestMode()) {
            $html[] = replace(
                array('{name}', '{value}'),
                array('test', '1'),
                $this::HTML_TEMPLATE_HIDDEN_FIELD
            );
        }

        return implode("\n", $html);
    }
}
