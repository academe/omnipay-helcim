## Notes on How Helcim Works

Following are some notes on how the Helcim gateway works. They help to put this gateway driver into context.

The Helcim service home page: https://www.helcim.com/

The Helcim site accepts either GET or POST data; they are completely interchangeable.
This gateway interface leaves the choice of which to use up to you. For a simple "donate" button or
open-ended payment form, GET would be most appropriate. If any further details need to be sent to
the form, such as customer address, names, basket detaill, the POST is more appropriate.

There are two modes of operation:

* **JavaScript mode** (`Helcim_JS`), where credit card details are taken in a form on your site, but submitted
   direct to Helcim from the form using AJAX. No customer leaves your site.
   Your site does not need PCI registration (strictly, it reduces the PCI compliance scope, so there is no room
   for complacancy), since the CC details are not submitted direct to your site (it is
   important that the form is constructed correctly to ensure this does not inadvertently happen.
   Version 1 of this interface supports only *payment* (not authorisation).
   This mode was launched in October 2014.
* **Direct mode** (`Helcim_Direct`), where credit card details are taken on your site, and no customer leaves your site.
  You will need to be PCI-registered to use this mode. Note that Helcim do not call this mode "Direct".
  This is their unnamed original API mode. It has been named "Direct" here to distininguish it from the
  later Hosted Pages mode.
* **Hosted Page mode** (`Helcim_HostedPages`), where the user is sent to one of a number of pre-defined forms on the Helcim site.
  You do not need to bve PCI-registered for this mode of operation, but there is a risk of details
  being passed between your site and Helcim being manipulated by the user.
  This is not ideal, and reconciliation therefore plays an important part in using this mode.

In the current incarnation, there is no back-channel when using the Hosted Page mode on its own. There are also
no hashes generated (using a secret known only to your site and Helcim) that can be be use to check
whether the data passed between the sites, through your browser, has been changed en-route.
With this in mind, you need to be careful not to trust the results of a successful transaction without
checking the details against the server first, i.e. don't ship the goods until you have checked the transaction
is in the Helcim account. There *is* a back-channel that your site can use to check the details of
a claimed transaction, and that is incorporated into this driver. More details on this below.

There is an API to get lists of transactions, so that can be used to check the validity of
a result posted back to your site. This will involve some overlap between the Hosted Pages and the Direct
modes of operation. Searching for a transaction by key phrase also looks in the order ID and transaction ID
fields. Since either of these IDs could concievably appear in any other part of any transaction, the
gateway driver is ready to accept more than one matching transaction and to sift through them to find
the one that matches the order ID or transaction ID exactly. This is certainly an edge-case, but needs to
be considered, as edge cases are where vulnerabilities can be exploited.

~~The fetching of transactions through the API does not include the transaction type, even though the
transaction type is available on the transaction details in the admin pages. In theory, an end user could
change the transaction type from `purchase` to `preAuth` without being detected by the interface,
and unless there are administration processes in place to catch this, and `capture` an authorised payment,
some payments could possibly be lost.~~ **UPDATE:** this is being corrected now. I'll update this
document and code when the transaction type is available through the API. **UPDATE** From August 2014 this
is now fixed - a fetched transaction will include the transaction type (or *action* in omnipay lingo).

### URLs

When using the Hosted Pages mode, the form will need the return URL set in advance. 
Unlike many payment gateways, the return URL is not provided at run-time by your application.

The cancel button on a Hosted Pages payment page will take the user back to the home page of your site,
which is not ideal. You may be able to disable and replace the cancel button with a more appropriate
URL using CSS (more likely JavaScript provided through CSS, which is a bit hacky).
You do have the ability to add CSS styles when setting up a payment form.

The Helcim gateway will return to your site via a POST. This means your site must have a valid SSL
certificate to accept the POST from the gateway running in SSL/HTTPS. POSTing from a secure Hosted Page
to an unsecure page on your site will result in a browser warning, and also in the results of the payment
being sent as clear-text (unencrypted). No credit card details are sent in clear text, but enough details
to be concerned are. So do ensure your return URL is SSL protected.

To get around the lack of programmable return URLs, and the lack of an "unauthorised" reponse back to
your site, I strongly suspect that the form will need to be used within am iframe to be able to control
the flow for the user more smoothly and more intuitively.

### Hosted Pages Return Data

It appears at the moment that the Helcim Hosted Page payment form will never return a "declined" or error
status to your site. The user will remain on the payment page until they either successfully get a card approved,
or fail a number of times are are sent to the "cancel" URL (the site home page, set up for the account, which
is NOT specific for each form).

### Hosted Pages Actions/Types

The Hosted Page mode supports `authorize` (aka preAuth) and `purchase` actions ("type" field) only.
The remaining actions are available through the direct API.

### Currency Identification

The currency is set at the merchant level. If you want to accept payments in several currencies, such 
as USD and CAD for North America, then you will need two merchant accounts.

The amount that is returned in the API when fetching transaction details, is formatted for display
with the currency symbol and possibly thousand-separators. All characters but digits and the decimal
point (.) should be stipped out to get to the raw value. Helcim uses only the full stop/period for
the decimal point at present, since it is North American based and does not have to support more
international formats.

### Authentication ID

When connecting to the Helcim forms or API, two identication parts are needed:

* Merchant ID
* Token

The Merchant ID is a numeric value and unque to your account. The token varies depending on how
it is used.

The Direct mode API has a single token defined for it. You would never allow end users to see that token, as
it gives full access to the API and the complete history of your transactions.
It is just used for back-end operations. This token can be renewed
any time there is a suspician it may have been compromised.

When running in Hosted Page mode, each form has its own token. Those tokens *are* visible to
end users when those users are redirected to the form(s).

### Other Questions

What appears on the bank statements when payments are made? No idea yet.

### Conclusions

This payment gateway is a bit of an odd-ball in some ways (but then, don't they all have their own
peculiarities). The Hosted Pages are okay for accepting donations or
taking payments that are reconciled manually by the recipient later. However, using the
Hosted Pages as the payment gateway for an e-commerce shop needs additional checks through the
Direct API, that are not obvious (certainly not highlighted in the documentation), but are covered
by this driver.

For the above reasons, I would recomment only using the Hosted Pages mode for authorisation and not
for taking full payments if used with a shop. I believe in some parts of the US, this would be a
requirement (the card cannot be charged until the items are shipped) but that is not always the
case in many other countries.

I have no comments on the Direct mode as I have not attempted to use that yet. The Direct mode
would need your site to be PCI compliant and registered, which is a whole other headeache that
is often best avoided.

With both modes, you *do* need a SSL certificate on your site, regardless of what the documentation
says.

The JavaScript mode is relatively new, and on the TODO list to implement.
