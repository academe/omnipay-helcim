<?php

namespace Omnipay\Helcim;

use Omnipay\Tests\GatewayTestCase;

// See https://github.com/thephpleague/omnipay-authorizenet/blob/master/tests/AIMGatewayTest.php for example

class HostedPagesGatewayTest extends GatewayTestCase
{
    protected $voidOptions;

    // Get a random value for a parameter.
    // Some setters have validation and some normalisation applied to them,
    // so we can't pass in the same uniqid to every parameter and expect the
    // same value to come back out, even if it passes the validation.

    protected function getParameterValue($key = '')
    {
        if ($key == 'merchantId') {
            // The merchantId must always be numeric.
            $value = mt_rand(32767, mt_getrandmax());
        } elseif ($key == 'method') {
            // The method must be either GET or POST.
            $value = (rand(0, 1) ? 'POST' : 'GET');
        } else {
            $value = uniqid();
        }

        return $value;
    }

    public function setUp()
    {
        parent::setUp();

        $this->gateway = new HostedPagesGateway($this->getHttpClient(), $this->getHttpRequest());

        $this->purchaseOptions = array(
            'amount' => '10.00',
            'card' => $this->getValidCard(),
        );

        $this->captureOptions = array(
            'amount' => '10.00',
            'transactionReference' => '12345',
        );

        $this->voidOptions = array(
            'transactionReference' => '12345',
        );
    }

    public function testDefaultParametersHaveMatchingMethods()
    {
        $settings = $this->gateway->getDefaultParameters();
        foreach ($settings as $key => $default) {
            $getter = 'get'.ucfirst($key);
            $setter = 'set'.ucfirst($key);

            $value = $this->getParameterValue($key);

            $this->assertTrue(method_exists($this->gateway, $getter), "Gateway must implement $getter()");
            $this->assertTrue(method_exists($this->gateway, $setter), "Gateway must implement $setter()");

            // setter must return instance
            $this->assertSame($this->gateway, $this->gateway->$setter($value));
            $this->assertSame($value, $this->gateway->$getter());
        }
    }

    public function testAuthorizeParameters()
    {
        if ($this->gateway->supportsAuthorize()) {
            foreach ($this->gateway->getDefaultParameters() as $key => $default) {
                // set property on gateway
                $getter = 'get'.ucfirst($key);
                $setter = 'set'.ucfirst($key);

                $value = $this->getParameterValue($key);

                $this->gateway->$setter($value);
                // request should have matching property, with correct value
                $request = $this->gateway->authorize();
                $this->assertSame($value, $request->$getter());
            }
        }
    }

    public function testCompleteAuthorizeParameters()
    {
        if ($this->gateway->supportsCompleteAuthorize()) {
            foreach ($this->gateway->getDefaultParameters() as $key => $default) {
                // set property on gateway
                $getter = 'get'.ucfirst($key);
                $setter = 'set'.ucfirst($key);

                $value = $this->getParameterValue($key);

                $this->gateway->$setter($value);
                // request should have matching property, with correct value
                $request = $this->gateway->completeAuthorize();
                $this->assertSame($value, $request->$getter());
            }
        }
    }

    public function testPurchaseParameters()
    {
        foreach ($this->gateway->getDefaultParameters() as $key => $default) {
            // set property on gateway
            $getter = 'get'.ucfirst($key);
            $setter = 'set'.ucfirst($key);

            $value = $this->getParameterValue($key);

            $this->gateway->$setter($value);
            // request should have matching property, with correct value
            $request = $this->gateway->purchase();
            $this->assertSame($value, $request->$getter());
        }
    }

    public function testCompletePurchaseParameters()
    {
        if ($this->gateway->supportsCompletePurchase()) {
            foreach ($this->gateway->getDefaultParameters() as $key => $default) {
                // set property on gateway
                $getter = 'get'.ucfirst($key);
                $setter = 'set'.ucfirst($key);

                $value = $this->getParameterValue($key);

                $this->gateway->$setter($value);
                // request should have matching property, with correct value
                $request = $this->gateway->completePurchase();
                $this->assertSame($value, $request->$getter());
            }
        }
    }

    // Just not sure how this would be tested.
    // We "send" a request, which just packages the data up. Then we "complete" the request
    // which does a redirect to Helcim. Sending the request involves no communication with Helcim.

    public function testAuthorizeSuccess()
    {
        //$this->setMockHttpResponse('HelcimHostedSuccess.txt');
        $response = $this->gateway->authorize($this->purchaseOptions)->send();

        // Not successful because a redirect is needed.
        $this->assertFalse($response->isSuccessful());

        $this->assertTrue($response->isRedirect());

        //$this->assertSame('2184493132', $response->getTransactionReference());
        //$this->assertSame('This transaction has been approved.', $response->getMessage());
    }
}
