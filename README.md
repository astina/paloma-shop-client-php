Paloma Shop PHP Client
========

PHP client library for the Paloma Shop. Facilitates the access to the following APIs (see https://swagger.astina.io/TODO for details):

- Catalog
- Checkout
- Customers

## Usage
Create Paloma Client
```php
$paloma = Paloma\Shop\Paloma::create('https://demo.paloma.one/api/', 'yourAPIKey'));
```
Call API, e.g. fetch catalog categories
```php
$categories = $paloma->catalog()->categories('ch', 'de');
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

Get product for a category, sorted by price:
```php
$page = $paloma->catalog()->search('ch', 'de', [
        'category' => 'animals', 
        'size' => 50,
        'sort' => 'price',
        'order' => 'desc'
    ]);
```

Get cart (e.g. to render shopping cart view):
```php
$order = $paloma->checkout()->cart('ch', 'de')->get();
```

Add product to cart:
```php
$order = $paloma->checkout()->cart('ch', 'de')->addItem('12345', 1]);
```

Update cart item quantity:
```php
$paloma->checkout()->cart('ch', 'de')->updateQuantity('123' /* order item id */, 2 /* quantity */);
```

Remove a cart item:
```php
$paloma->checkout()->cart('ch', 'de')->removeItem('123');
```

Get cart items count:
```php
// Number of order items
$paloma->checkout()->cart('ch', 'de')->itemsCount();

// Number of items times quantities
$paloma->checkout()->cart('ch', 'de')->unitsCount();
```

Set order customer:
```php
$paloma->checkout()->cart('ch', 'de')->setCustomer([
    'name' => 'Hans Muster',
    'emailAddress' => 'test@astina.io',
    'gender' => 'male',
]);
```

Set order addresses:
```php
$billingAddress = [ /* ... */ ];
$shippingAddress = [ /* ... */ ];
$paloma->checkout()->cart('ch', 'de')->setAddresses($billingAddress, $shippingAddress);
```

Initialize payment:
```php
$payment = $paloma->checkout()->cart('ch', 'de')->initPayment([
    'successUrl' => 'https://example.org/checkout/success',
    'cancelUrl' => 'https://example.org/checkout/cancel',
    'errorUrl' => 'https://example.org/checkout/error',
]);
```

Use `$payment['providerRequest']['params']` to create payment URL or to render payment form.

Place the order:
```php
$order = $paloma->checkout()->cart('ch', 'de')->purchase();
```