Helcim JS V1 (Purchase only)
============================

Introduction
------------

This mode works like this:

The form is created on your own site. You include:

1. The credit card fields, but do not given them names, so they do not get submitted to your site.
2. Address and order details, some hidden and some changeable by the user. These are largely optional.
3. Custom fields that only your site will see.

The helcim.js script is referenced, and that takes over the submission of the form, and the
reporting of any errors.

When the user submits the form, the following happens:

1. Credit card details and address/order details are sent using AJAX to Helcim.
2. Any authentication errors are then reported to the user on the page, with any
   appropriate retry attempts allowed. Nothing is submitted to your site until
   authentication is successful.
3. If successful, the result of the authentication is inserted into the form as
   hidden fields and the form is submitted to your server.
4. The server should then use the Direct API to fetch the details of the transaction
   from the Helcim server.
5. The transaction will be an authorisation only, so a Direct `capture` will be needed
   later to take the authorised payment.

As for the Hosted Pages mode, the data POSTed back to your site can be manipulated by
the end user, and so should not be trusted. For this reason, it is important to create
an order ID and track that in the session. The order ID can then be used to fetch the
transaction from Helcim and compare it to what was submitted.

Conceptually, the data POSTed to your site from the form on your site, works in the same
way as data submitted from a Helcim Hosted Pages form. The data and its handling may
actually be identical (TBC).

The `JSpurchaseRequest` class is used as a helper for generating the initial form. The
`JScompletePpurchaseRequest` class handles the data submitted by the payment form to your
server, and validates the transaction against Direct API.

If the transaction fails, then the POSTed response to the server will include a "response"
value of zero.

There are a few issues with the documentation that need to be cleared up:

1. The documentation states that sensitive credit card data is never submitted to your server,
   and the user-entered expiry date does not have a field name, meaning that it is not submitted.
   However, after a successful authorisation, `expiryDate` is submitted back to your server
   anyway after being inserted by the JavaScript.
2. The documentation states that the helcim.js API supports only "purchase" transactions. It then
   describes only "preauth" (authorisation) transactions. I suspecty the former is a typo and the
   latter is the reality. The amount field is optional, with a non-present amount being used
   just to obtain a card token. The amount field is not actually listed in the fields list, and
   only appears in the PHP code sample (and with two "id" attributes, one of which is assumed to
   be a type of "name", or maybe not).

Several differences to note with this API, just to make sure there are enough exceptions to keep
us busy:

1. The javascript is loaded from the same URL regardless of whether running against the test or
   the live system. Instead, a `dev` field indicates which system the form will be POSTed to.
2. The other interfaces require a `allowZeroAmount` flag to be set if a zero amount is being
   submitted. This API does not. The other APIs also make the amount mandatory, even if zero,
   while this API leaves it entirely optional, so it can be left off the form.

