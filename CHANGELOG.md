# Changelog NoPayn payment plugin

** 1.0.0 **

* Initial version

** 1.0.1 **

* Implemented Capture on Complete feature
* Implemented Void on Cancelled feature
* Updated translations

** 1.0.2 **

* Updated Description

** 1.0.3 **

* Updated Tested up version

** 1.0.4 **

* Improved void on Cancelled func

** 1.0.5 **

* Fixed bug: Void transaction was created incorrectly

** 1.0.6 **

* Fixed bug with Void functionality
* Fixed bug with order status mapping

** 1.0.7 **

* Set default expiration time to 5 minutes for orders and transactions

** 1.0.8 **

* Provided possibility to customize the expiration period in main module settings
* Fixed bug: plugin was updating the order status even when it was already set to a final state in the store.
  
** 1.0.9 **

* Fixed Bug: Where completing an order triggered an error in case order is not related to our plugin.

** 1.0.10 **

* Fixed Bug: Where the order status did not update after a successful retry following a failed payment.