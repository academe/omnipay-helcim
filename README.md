# Omnipay: Helcim Gateway

[![Build Status](https://travis-ci.org/academe/omnipay-helcim.png?branch=master)](https://travis-ci.org/academe/omnipay-helcim)
[![Latest Stable Version](https://poser.pugx.org/academe/omnipay-helcim/version.png)](https://packagist.org/packages/academe/omnipay-helcim)
[![Total Downloads](https://poser.pugx.org/academe/omnipay-helcim/d/total.png)](https://packagist.org/packages/academe/omnipay-helcim)


**Helcim driver for the Omnipay v2 PHP payment processing library**

Work in progress. First thing to get working is the Helcim Hosted Pages for purchases.

This package is a driver for [Omnipay](https://github.com/thephpleague/omnipay) at August 2014.
Being a *driver* rather than an *interface*, it does not 
interface directly with the Helcim service, but instead sits between Omnipay and the Helcim gateway.

This package lives in the namespace `Omnipay/Helcim`

It supports both GET and POST. It will aim to support all features and actions of the gateway,
but starting just with the Hosted Pages mode for use on a current project (the itch being scratched).

If you want to contribute to this driver, please get in touch.

## Links to Documentation

There are some notes on 
[How Helcim Works](https://github.com/academe/omnipay-helcim/blob/master/docs/How-Helcim-Works.md)
that are worth reading to understand some of the challenges that were presented.

The [network flow chart can be found here](https://github.com/academe/omnipay-helcim/blob/master/docs/omnipay-helcim-hostedpages.pdf).
It should help to put things into context.
It is a first draft, and probably uses the wrong types of arrows for the data, process and user flows,
so any experience in this type of chart would be most appreciated. Bear in mind, its aim is to
help a developer to see what needs to be developed, where debug hooks can be put in when things
don't work quite as expected, and to help get some eyes on any security issues with the payment flow.

[Sample code for using this gateway](https://github.com/academe/omnipay-helcim/blob/master/docs/HostedPages-Purchase.md)
is also in the docs section. It assumes you are familiar with the OmniPay environment,
and tries to highlight any peculiarities of Helcim.

## Tests

No, none yet. If you can help, then please do. Would be much appreciated.

## TODO

Functionality still to be implemented (ticked off when done):

* [x] Direct Address Verification (Note 2)
* [x] Direct Authorization
* [x] Direct Capture
* [x] Direct Purchase
* [x] Direct Refund
* [x] Direct Void
* [ ] Direct Recuring Request
* [x] Transaction History
  * [x] Fetch One
  * [x] Fetch List (Note 1)
* [x] Hosted Pages Authorize
* [x] Hosted Pages Purchase
* [x] Access to Hosted Pages custom fields
* [ ] Helcim.js Payment/Capture Card Details

Notes:

1. ~~Lists of transactions can be fetched, but it returns an array of XML objects at present.
   That is not going to be as useful as it could be. Maybe we need a transaction object.~~
2. ~~It is not clear whether address verification can be run by itself, or whether it runs as
   an added (optional) benefit to the authorize and purchase actions.~~ Address verification is
   not a standalone service. It is an additional field that can be passed in with other
   authorisation-based transactions to request additional checks to be performed on the
   transaction. This returns additional flags indicating the address verification status, but
   so far as I know, does not affect the authorisation result.
