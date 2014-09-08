Helcim Hosted Pages (Authorize and Purchase)
============================================

Introduction
------------

In this mode, you create a form on the Helcim site, known as "Payment Pages".
The form is given an Approvment URL, where the user will be taken if the
transaction is successful.

This method uses no call-backs. The user is POSTed to the form with the amount
and any known personal details. Helcim then POSTs the result back to the Approval
URL.

There are no hashes involved, so the data POSTed in either direction cannot be trusted.
Make sure you save the amount you wish to charge, and the transactionId you create,
into the session. These will be used for checking the results at the end.

This driver does have a back-channel to the Helcim Direct API, and that is used to
fetch the final transaction, which *can* be trusted.

Helcim Hosted Pages supports payment and authoriziation. To capture any authorized sums,
the Direct API is used.

Initialization
--------------

The following sample will initialize a request for payment using the minimum of details:

```php
    // Get the driver.
    $gateway = Omnipay::create('Helcim_HostedPages');
    
    // Set credentials.
    // The gateway token is for the Direct access and the form token identifies
    // the form you want to use.
    $gateway->setMerchantId('...');
    $gateway->setGatewayToken('...');
    $gateway->setFormToken('...');
    
    // Generate your transaction ID.
    // Must be unique and not guessable, unlike this example.
    $transactionId = date('Y-m-d_H-i-s');
    
    // Optional personal details where known.
    // See OmniPay documentation for usage.
    // The card and CVV data passed in hwre will be ignored.
    $card = new CreditCard([...]);
    
    // Send the transaction to OmniPay.
    $response = $gateway->purchase([
        'amount' => 10.00,
        'transactionId' => $transactionId,
        'card' => $card,
        'description' => "Mandatory description",
        'customerId' => 'Mandatory customer ID',
    ])->send();
    
    // Alternatively, $gateway->autyhorize(...) for just authorization.
    
    // Standard OmniPay handling.
    // So long as mandatory details look okay, then a redirect will be
    // the required action.
    if ($response->isSuccessful()) {
        // Payment was successful. Nothing more to do.
        // This will never happen for this driver.
        print_r($response);
    } elseif ($response->isRedirect()) {
        // Redirect to offsite payment gateway.
        $response->redirect();
    } else {
        // Payment failed: display the error message.
        echo $response->getMessage();
    }
```

Approval Handling
-----------------

Helcim will POST a successful payment authorization to the Approval URL set up in the form.
The Approval URL should handle the result as in the example below:

```php
    // Set up the gateway.
    $gateway = Omnipay::create('Helcim_HostedPages');
    
    // Set credentials.
    // The Form Token is not needed for this stage.
    $gateway->setMerchantId('...');
    $gateway->setGatewayToken('...');
    
    // Get your transaction ID saved in the session before the redirect, and
    // give it to the driver.
    $gateway->setTransactionId($your_transaction_id);
    
    // Complete the purchase with OmniPay.
    // It is important to use the right complete method.
    $response = $gateway->completePurchase()->send();
    // or
    $response = $gateway->completeAuthorize()->send();
    
    // Check the result.
    if ($response->isSuccessful()) {
        echo "Success!";
        // Don't forget to process the cart or basket to complete the transaction.
        // The transaction details are available for logging and capturing (perhaps
        // to create a new account). This includes the authorization code and the
        // amount that was paid, plus all personal details and a card summary.
        $transaction_XML = $response->getTransaction();
        $transaction_Array = $response->getTransactionArray();
    } else {
        echo $response->getErrorMessage();
    }
```
