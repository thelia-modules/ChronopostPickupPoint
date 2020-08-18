# Chronopost

Allows you to choose between differents pickup point delivery modes offered by Chronopost.
Activating one or more of them will let your customers choose which one
they want.

Delivery types currently availables :

- Chrono13 BAL (pickup points/relay delivery in France)
- Others will be added in future versions

NB1 : You need IDs provided by Chronopost to use this module.


## Installation

### Manually

* Copy the module into ```<thelia_root>/local/modules/``` directory and be sure that the name of the module is ChronopostPickupPoint.
* Activate it in your thelia administration panel

### Composer

Add it in your main thelia composer.json file

```
composer require thelia/chronopost-pickup-point-module:~1.0
```

## Usage

First, go to your back office, tab Modules, and activate the module Chronopost.
Then go to Chronopost configuration page, tab "Advanced Configuration" and fill the required fields.

After activating the delivery types you wih to use, new tabs will appear. With these, you can
change the shipping prices according to the delivery type and the area, and/or activate free shipping for a given price and/or given area, or just
activate it no matter the are and cart amount.

If you also have the ChronopostLabel module, you can then generate and download labels from the Chronopost Label page accessible from the toolbar on the left of the BackOffice, or directly from the order page.
---
For relay / pickup points, you need to integrate a template to choose one relay in the list provided by the loop 'chronopost.pickup.point.get.relay'.
Ideally from a map, like google maps, yandex, or similar. 

Then, you can either create an entry in the address table and use it immediately,
or overload the thelia.order.delivery form to accept entire addresses as input instead of only an address id
from which to get the entire address.

## Loop

To be written

##Integration

There is no map integration example as of the 1.0.0. As such, you will have to make one yourself (you may use the ColissimoPickupPoint one as an example as they work similarily).