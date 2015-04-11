<?php

namespace Omnipay\Helcim\Message;

use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Common\Message\RequestInterface;

/**
* Helcim Direct Authorize and Purchase Response
*/
class DirectResponse extends AbstractResponse implements ResponseInterface
{
    /**
     * The data will be a Guzzle body object that evaluates into a string
     * containing a list of "name=value" strings, separated into separate lines.
     * Or it is a guzzle XML response.
     */
    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        //var_dump($data); //Guzzle\Http\EntityBody

        if (is_a($data, 'Guzzle\\Http\\EntityBody')) {
            // Take the body as a text string and split into lines.
            // We do not know if the lines will be terminated in Unix, Windows, or Mac
            // convention, or even a mix, so we accept them all.

            $lines = preg_split('/[\n\r]+/', (string)$data);

            $result = array();

            foreach ($lines as $line) {
                // Skip any blank or extraneous lines.
                if (strpos($line, '=') === false) {
                    continue;
                }

                // We assume nothing needs to be trimmed.
                list($name, $value) = explode('=', $line, 2);
                $result[$name] = $value;
            }
        } elseif (is_a($data, 'SimpleXMLElement')) {
            // FIXME: what happens here?
        }

        // Now we have the returned fields.
        $this->data = $result;
    }

    /**
     * Returns true if the transaction is complete and authorised, and there are no
     * further steps, such as redirects to complete 3DAuth.
     */
    public function isSuccessful()
    {
        return ! empty($this->data['response']);
    }

    public function getMessage()
    {
        return isset($this->data['responseMessage']) ? $this->data['responseMessage'] : null;
    }
}
