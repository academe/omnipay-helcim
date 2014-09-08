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


