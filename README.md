Paloma Shop PHP Client
========

PHP client library for the Paloma Shop. Facilitates the access to the following APIs (see https://docs.paloma.one/ for details and code examples):

- Catalog
- Checkout
- Customers

## Usage

Create Paloma client
```php
$client = Paloma\Shop\Paloma::createClient('https://demo.paloma.one/api/', 'yourAPIKey', 'yourChannel', 'yourLocale'));
```

Create Paloma service
```php
$paloma = new Paloma\Shop\Paloma($client, $userProvider, $validator);
```

Call API, e.g. fetch catalog categories
```php
$categories = $paloma->catalog()->getCategories();
```

## Configuration
Use dedicated `LoggerInterface` instance
```php
$client = Paloma\Shop\Paloma::createClient('https://demo.paloma.one/api/', 'yourAPIKey', $myLogger);
```

Use profiler to collect data for Symfony Profiler.
```php
$client = Paloma\Shop\Paloma::createClient('https://demo.paloma.one/api/', 'yourAPIKey', $myLogger, $myProfiler);
```

## Examples

_Hint_: Find more examples at https://docs.paloma.one/.

Get product for a category, sorted by price:
```php
$page = $paloma->catalog()->search(new SearchRequest(...));
```

Get cart (e.g. to render shopping cart view):
```php
$order = $paloma->checkout()->getCart();
```

Add product to cart:
```php
$order = $paloma->checkout()->addCartItem('12345', 1);
```

Update cart item quantity:
```php
$paloma->checkout()->updateCartItem('123' /* order item id */, 2 /* quantity */);
```

Remove a cart item:
```php
$paloma->checkout()->removeCartItem('123');
```

Get cart items count:
```php
// Number of order items
$paloma->checkout()->getCart()->itemsCount();

// Number of items times quantities
$paloma->checkout()->getCart()->unitsCount();
```

Set order addresses:
```php
$billingAddress = new Address(...);
$shippingAddress = new Address(...);
$paloma->checkout()->setAddresses($billingAddress, $shippingAddress);
```

Initialize payment:
```php
$payment = $paloma->checkout()->initializePayment(new PaymentInitParameters(...));
```

Use `$payment->getProviderParams()` to create payment URL or to render payment form.

Place the order:
```php
$orderPurchase = $paloma->checkout()->purchase();
echo 'Purchased order ' . $orderPurchase->getOrderNumber() . '!';
```
