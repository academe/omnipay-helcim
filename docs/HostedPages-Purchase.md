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


