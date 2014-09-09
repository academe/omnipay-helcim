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

## Notes on How Helcim Works

This section has been moved to [How Helcim Works](https://github.com/academe/omnipay-helcim/blob/master/docs/How-Helcim-Works.md)

The [network flow chart can be found here](https://github.com/academe/omnipay-helcim/blob/master/docs/omnipay-helcim-hostedpages.pdf).
It should help to put things into context.

[Sample code for using this gateway](https://github.com/academe/omnipay-helcim/blob/master/docs/HostedPages-Purchase.md)
is also in the docs section. It assumes you are familiar with the OmniPay environment,
and tries to highlight any peculiarities of Helcim.
