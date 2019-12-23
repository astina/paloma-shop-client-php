Paloma Shop PHP Client
========

PHP client library for the Paloma Shop. Facilitates the access to the following APIs (see https://docs.paloma.one/ for details and code examples):

- Catalog
- Checkout
- Customers

## Usage

```php

// Create Paloma client
$factory = new Paloma\Shop\PalomaClientFactory($options);

$client = $factory->create([
    'base_url' => 'https://demo.paloma.one/api/', 
    'api_key' => 'yourAPIKey',
    'channel' => 'yourChannel',
    'locale' => 'yourLocale',
]);

// Create security service
$security = new MyPalomaSecurity(); // implements \Paloma\Shop\Security\PalomaSecurityInterface

// Create Paloma catalog
$catalog = new \Paloma\Shop\Catalog\Catalog($client, new \Paloma\Shop\Common\PricingContextProvider($security));

// Call API, e.g. fetch catalog categories
$categories = $catalog->getCategories();

// Create Symfony validator
$validator = new Validator(); // implements Symfony\Component\Validator\Validator\ValidatorInterface

// Create Paloma checkout
$checkout = new \Paloma\Shop\Checkout\Checkout($client, $security, $validator);

// Add cart item
$checkout->addCartItem('100-200', 1);

```

## Examples

_Hint_: Find more examples at https://docs.paloma.one/.

Get product for a category, sorted by price:
```php
$page = $catalog->search(new SearchRequest(...));
```

Get cart (e.g. to render shopping cart view):
```php
$order = $checkout->getCart();
```

Add product to cart:
```php
$order = $checkout->addCartItem('12345', 1);
```

Update cart item quantity:
```php
$checkout->updateCartItem('123' /* order item id */, 2 /* quantity */);
```

Remove a cart item:
```php
$checkout->removeCartItem('123');
```

Get cart items count:
```php
// Number of order items
$checkout->getCart()->itemsCount();

// Number of items times quantities
$checkout->getCart()->unitsCount();
```

Set order addresses:
```php
$billingAddress = new Address(...);
$shippingAddress = new Address(...);
$checkout->setAddresses($billingAddress, $shippingAddress);
```

Initialize payment:
```php
$payment = $checkout->initializePayment(new PaymentInitParameters(...));
```

Use `$payment->getProviderParams()` to create payment URL or to render payment form.

Place the order:
```php
$orderPurchase = $checkout->purchase();
echo 'Purchased order ' . $orderPurchase->getOrderNumber() . '!';
```
