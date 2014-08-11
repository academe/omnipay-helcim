# Omnipay: Helcim

**Helcim driver for the Omnipay PHP payment processing library**

Work in progress. First thing to get working is the Helcim Hosted Pages for purchases.

This package provides the namespace Omnipay/Helcim

It supports both GET and POST. It will aim to support all features and actions of the gateway.

## Notes on How Helcim Works

Following are some notes on how the Helcim gateway works. They help to put this gateway interfave into context.

The Helcim site accepts either GET or POST data; they are completely interchangeable.
This gateway interface leaves the choice of which to use up to you. For a simple "donate" button or
open-ended payment form, GET would be most appropriate. If any further details need to be sent to
the form, such as customer address, names, basket detaill, the POST is more appropriate.

There are two modes of operation:

* Direct mode, where credit card details are taken on your site, and no customer leaves your site.
  You will need to be PCI-registered to use this mode.
* Hosted Page mode, where the user is sent to one of a number of pre-defined forms on the Helcim site.
  You do not need to bve PCI-registered for this mode of operation, but there is a risk of details
  being passed between your site and Helcim being manipulated by the user.
  This is not ideal, and reconciliation therefore plays an important part in using this mode.

In the current incarnation, there is no back-channel when using the Hosted Page mode. There are also
no hashes generated (using a secret known only to your site and Helcim) that can be be use to check
whether the data passed between the sites, through your browser, has been changed en-route.
With this in mind, you need to be careful not to trust the results of a successful transaction without
some kind of reconciliation first. i.e. don't ship the goods until you have checked teh transactions
in the Helcim account.

When using the Hosted Page mode, the page will need the return URL set in advance. 
Unlike many payment gateways, the return URL is not provided at run-time by your application.

The cancel button on a Hosted Page payment page will take the user back to the home page or your site,
which is not ideal. You may be able to disable and replace the cancel button with a more apprpriate
URL using CSS. You do have control of CSS styles when setting up a payment form.
