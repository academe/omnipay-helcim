# Omnipay: Helcim Gateway

**Helcim driver for the Omnipay PHP payment processing library**

Work in progress. First thing to get working is the Helcim Hosted Pages for purchases.

This package is a driver for Omnipay. Being a *driver* rather than an *interface*, it does not 
interface directly with the Helcim service, but rather tells Omnipay how to interact.

This package provides the namespace `Omnipay/Helcim`

It supports both GET and POST. It will aim to support all features and actions of the gateway,
but starting just with the Hosted Pages mode for use on a current project (the itch being scratched).

## Notes on How Helcim Works

Following are some notes on how the Helcim gateway works. They help to put this gateway interfave into context.

Service home page: https://www.helcim.com/

The Helcim site accepts either GET or POST data; they are completely interchangeable.
This gateway interface leaves the choice of which to use up to you. For a simple "donate" button or
open-ended payment form, GET would be most appropriate. If any further details need to be sent to
the form, such as customer address, names, basket detaill, the POST is more appropriate.

There are two modes of operation:

* **Direct mode** (`Helcim_Direct`), where credit card details are taken on your site, and no customer leaves your site.
  You will need to be PCI-registered to use this mode. Note that Helcim do not call this mode "Direct".
  This is their unnamed original API mode. It has been named "Direct" here to distininguish it from the
  later Hosted Pages mode.
* **Hosted Page mode** (`Helcim_HostedPages`), where the user is sent to one of a number of pre-defined forms on the Helcim site.
  You do not need to bve PCI-registered for this mode of operation, but there is a risk of details
  being passed between your site and Helcim being manipulated by the user.
  This is not ideal, and reconciliation therefore plays an important part in using this mode.

In the current incarnation, there is no back-channel when using the Hosted Page mode. There are also
no hashes generated (using a secret known only to your site and Helcim) that can be be use to check
whether the data passed between the sites, through your browser, has been changed en-route.
With this in mind, you need to be careful not to trust the results of a successful transaction without
some kind of reconciliation first. i.e. don't ship the goods until you have checked teh transactions
in the Helcim account.

There is an API to get lists of transactions, so that can *probably* be used to check the validity of
a result posted back to your site. This will involve some overlap between the Hosted Pages and the Direct
modes of operation. *(Note: the API may not actually allow fetching of transactions by transaction ID at the moment. I am assured this situation is being fixed.)*

The fetching of transactions through the API does not include the transaction type, even though the
transaction type is available on the transaction details in the admin pages. In theory, an end user could
change the transactino type from `purchase` to `preAuth` without being detected by the interface,
and unless there are administration processes in place to catch this, and `capture` an authorised payment,
some payments could possibly be lost. **UPDATE:** this is being corrected now. I'll update this
document and code when the transaction type is available through the API.

### URLs

When using the Hosted Page mode, the page will need the return URL set in advance. 
Unlike many payment gateways, the return URL is not provided at run-time by your application.

The cancel button on a Hosted Page payment page will take the user back to the home page or your site,
which is not ideal. You may be able to disable and replace the cancel button with a more apprpriate
URL using CSS. You do have the ability to add CSS styles when setting up a payment form.

The Helcim gateway will return to your site via a POST. This means your site must have a valid SSL
certificate to accept the POST from the gateway running in SSL/HTTPS. POSTing from a secure Hosted Page
to an unsecure page on your site will result in a browser error, and also in the results of the payment
being sent as clear-text (unencrypted).

To get around the lack of programmable return URLs, and the lack of an "unauthorised" reponse back to
your site, I strongly suspect that the form will need to be used within am iframe to be able to control
the flow for the user more smoothly and more intuitively.

### Hosted Pages Return Data

It appears at the moment that the Helcim Hosted Page payment form will never return a "declined" or error
status to your site. The user will remain on the payment page until they either successfully get a card approved,
or fail a number of times are are sent to the "cancel" URL (the site home page, set up for the account
- NOT specific for each form).

### Hosted Pages Actions/Types

The Hosted Page mode supports `authorize` (aka preAuth) and `purchase` actions ("type" field) only.
The remaining actions are available through the direct API. However, it is not possible to tell which
action was used based on the response data, as they are identical in format. To ensure the correct
method is called (`completeAuthorize()` or `completePurchase()`) some other method needs to be used,
most likely a flag in the session.

### Currency Identification

The currency is set at the merchant level. If you want to accept payments in several currencies, such 
as USD and CAD for North America, then you will need two merchant accounts.

The amount that is returned in the API when fetching transaction details, is formatted for display
with the currency symbol and possibly thousand-separators. All characters but digits and the decimal
point (.) should be stipped out to get to the raw value.

### Authentication ID

When connecting to the Helcim forms or API, two identication parts are needed:

* Merchant ID
* Token

The Merchant ID is a numeric value and unque to your account. The token varies depending on how
it is used.

The API has a single token defined for it. You would never allow end users to see that token, as
it gives full access to the API. It is just used for back-end operations. This token can be renewed
any time there is a suspician it may have been compromised.

When running in Hosted Page mode, each form has its own token. Those tokens *are* visible to
end users when those users are redirected to the form(s).

### Other Questions

What appears on the bank statements when payments are made? No idea yet.

### Conclusions

This payment gateway is a bit of an odd-ball. The Hosted Pages are okay for accepting donations or
taking payments that are reconciled manually by the recipient later. However, using the
Hosted Pages as the payment gateway for an e-commerce shop is fraught with potential problems,
which I am still trying to find workarounds for. Use with caution, in the meantime.

The developers are taking some of the issues on-board and working on fixes, so I will keep
this document updated as we go along.

I have no comments on the Direct mode as I have not attempted to use that yet. The Direct mode
would need your site to be PCI compliant and registered, which is a whole other headeache that
is best avoided.

With both modes, you *do* need a SSL certificate on your site, regardless of what the documentation
says.
