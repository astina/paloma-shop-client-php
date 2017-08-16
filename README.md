Paloma Shop PHP Client
========

PHP client library for the Paloma Shop. Facilitates the access to the following APIs (see https://swagger.astina.io/TODO for details):

- Catalog
- Checkout
- Customers

## Usage
Create Paloma Client
```php
$paloma = new Paloma(new CatalogClient('https://demo-shop.paloma.one:8187/api', 'yourAPIKey'),
                     new CheckoutClient('https://demo-shop.paloma.one:8188/api', 'yourAPIKey'),
                     new CustomersClient('https://demo-shop.paloma.one:8189/api', 'yourAPIKey'));
```
Call API, e.g. fetch catalog
```php
$categories = $paloma->catalog()->categories('ch', 'de');
```

## Configuration
Enable debug mode (detailed logging of requests/responses)
```php
$catalogClient = new CatalogClient('https://demo-shop.paloma.one:8187/api', 'yourAPIKey', true)
```
Use dedicated `LoggerInterface` instance
```php
$catalogClient = new CatalogClient('https://demo-shop.paloma.one:8187/api', 'yourAPIKey', true, $myLogger);
```

