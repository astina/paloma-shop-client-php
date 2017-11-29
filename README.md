Paloma Shop PHP Client
========

PHP client library for the Paloma Shop. Facilitates the access to the following APIs (see https://docs.paloma.one/ for details and code examples):

- Catalog
- Checkout
- Customers

## Usage
Create Paloma Client
```php
$paloma = Paloma\Shop\Paloma::create('https://demo.paloma.one/api/', 'yourAPIKey', 'yourChannel', 'yourLocale'));
```
Call API, e.g. fetch catalog categories
```php
$categories = $paloma->catalog()->categories();
```
## Configuration
Use dedicated `LoggerInterface` instance
```php
$catalogClient = new CatalogClient('https://demo.paloma.one/api/catalog/', 'yourAPIKey', $myLogger);
```

Use profiler to collect data for Symfony Profiler.
```php
$catalogClient = new CatalogClient('https://demo.paloma.one/api/catalog/', 'yourAPIKey', $myLogger, $myProfiler);
```

## Examples

_Hint_: Find more examples at https://docs.paloma.one/.

Get product for a category, sorted by price:
```php
$page = $paloma->catalog()->search([
        'category' => 'animals', 
        'size' => 50,
        'sort' => 'price',
        'order' => 'desc'
    ]);
```

Get cart (e.g. to render shopping cart view):
```php
$order = $paloma->checkout()->cart()->get();
```

Add product to cart:
```php
$order = $paloma->checkout()->cart()->addItem('12345', 1]);
```

Update cart item quantity:
```php
$paloma->checkout()->cart()->updateQuantity('123' /* order item id */, 2 /* quantity */);
```

Remove a cart item:
```php
$paloma->checkout()->cart()->removeItem('123');
```

Get cart items count:
```php
// Number of order items
$paloma->checkout()->cart()->itemsCount();

// Number of items times quantities
$paloma->checkout()->cart()->unitsCount();
```

Set order customer:
```php
$paloma->checkout()->cart()->setCustomer([
    'name' => 'Hans Muster',
    'emailAddress' => 'test@astina.io',
    'gender' => 'male',
]);
```

Set order addresses:
```php
$billingAddress = [ /* ... */ ];
$shippingAddress = [ /* ... */ ];
$paloma->checkout()->cart()->setAddresses($billingAddress, $shippingAddress);
```

Initialize payment:
```php
$payment = $paloma->checkout()->cart()->initPayment([
    'successUrl' => 'https://example.org/checkout/success',
    'cancelUrl' => 'https://example.org/checkout/cancel',
    'errorUrl' => 'https://example.org/checkout/error',
]);
```

Use `$payment['providerRequest']['params']` to create payment URL or to render payment form.

Place the order:
```php
$order = $paloma->checkout()->cart()->purchase();
```
