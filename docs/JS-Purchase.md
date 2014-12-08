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

As for teh Hosted Pages mode, the data POSTed back to your site can be manipulated by
the end user, and so should not be trusted. For this reason, it is important to create
an order ID and track that in the session. The order ID can then be used to fetch the
transaction from Helcim and compare it to what was submitted.

Conceptually, the data POSTed to your site from the form on your site, works in the same
way as data submitted from a Helcim Hosted Pages form. The data and its handling may
actually be identical (TBC).

The `JSpurchaseRequest` class is used as a helper for generating the initial form. The
`JScompletePpurchaseRequest` class handles the data submitted by the payment form to your
server, and validates the transaction against Direct API.

TO CHECK: what happens when failed retry attempts are exceeded? Is the final result
still submitted to your site?

