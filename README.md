# Sylius IMoje.pl payment gateway plugin

Integration of Nets (Netaxept) payment gateway to Sylius as a new payment method.

Big thanks to [FDM](https://github.com/fdmweb) and his [Netaxept Payum bundle](https://github.com/fdmweb/FDM-netaxept).

Check my other Sylius plugin for [IMoje.pl payments](https://github.com/frontycore/sylius-imoje).

## Installation

```bash
$ composer require fronty/sylius-netaxept
```

Add plugin dependencies to your AppKernel.php file:

```php
public function registerBundles()
{
    return array_merge(parent::registerBundles(), [
        ...

        new \Fronty\SyliusNetaxeptPlugin\FrontySyliusNetaxeptPlugin(),
    ]);
}
```

## Beware
This plugin introduces only very simple Netaxept implementation, it only handles payment registration and authorization. It doesn't do any refunds when order is storned etc.
This plugin is not tested, therefore it's not an official Sylius plugin. Please be careful.
If you'd like to add testing, you are more than welcome to send PR!

## Usage
### Sandbox (testing mode)
Go to Sylius admin and create new payment method of Netaxept type. Choose Testing environment and fill in the Merchant ID and Token fiels.
You should receive these credentials when you registered a Netaxept account.

### Production
After you receive Netaxept production credentials, fill these to the payment method fields and switch the environment to Production. That's all :)


## Todo
- Tests to become an official Sylius plugin :)
- Refunds implementation
- Separate Register, Capture and Authorize Payum actions (everything is in capture action now)
- Notification implementation