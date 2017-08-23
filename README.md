Paloma Shop PHP Client
========

PHP client library for the Paloma Shop. Facilitates the access to the following APIs (see https://swagger.astina.io/TODO for details):

- Catalog
- Checkout
- Customers

## Usage
Create Paloma Client
```php
$paloma = new Paloma(new CatalogClient('https://demo.paloma.one/api/catalog/', 'yourAPIKey'),
                     new CheckoutClient('https://demo.paloma.one/api/checkout/', 'yourAPIKey'),
                     new CustomersClient('https://demo.paloma.one/api/customers/', 'yourAPIKey'));
```
Call API, e.g. fetch catalog categories
```php
$categories = $paloma->catalog()->categories('ch', 'de');
```
## Configuration
Enable debug mode (detailed logging of requests/responses)
```php
$catalogClient = new CatalogClient('https://demo.paloma.one/api/catalog/', 'yourAPIKey', true)
```
Use dedicated `LoggerInterface` instance
```php
$catalogClient = new CatalogClient('https://demo.paloma.one/api/catalog/', 'yourAPIKey', true, $myLogger);
```

