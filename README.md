# Omnipay: Helcim Gateway

**Helcim driver for the Omnipay PHP payment processing library**

Work in progress. First thing to get working is the Helcim Hosted Pages for purchases.

This package is a driver for [Omnipay](https://github.com/thephpleague/omnipay).
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

No, none yet. If you can help, then please do.

## TODO

Functionality still to be implemented:

* [ ] Direct Address Verification
* [ ] Direct Authorization
* [ ] Direct Capture
* [ ] Direct Purchase
* [ ] Direct Refund
* [ ] Direct Void
* [ ] Direct Transaction Search
* [x] Hosted Pages Authorize
* [x] Hosted Pages Purchase

