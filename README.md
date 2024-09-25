# A simple keez.ro library

[![MIT Licensed](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

The Keez PHP library provides convenient access to the Keez.ro API from
applications written in the PHP language. It includes a pre-defined set of
classes for API resources that initialize themselves dynamically from API
responses which makes it compatible with a wide range of versions of the Keez
API.

## Requirements

PHP 7.3 and later.

## Composer

You can install the bindings via [Composer](http://getcomposer.org/). Run the following command:

```bash
composer require sypher/keez
```

To use the bindings, use Composer's [autoload](https://getcomposer.org/doc/01-basic-usage.md#autoloading):

```php
require_once('vendor/autoload.php');
```

## Dependencies

The bindings require the following extensions in order to work properly:

-   [`curl`](https://secure.php.net/manual/en/book.curl.php)
-   [`json`](https://secure.php.net/manual/en/book.json.php)
-   [`mbstring`](https://secure.php.net/manual/en/book.mbstring.php) (Multibyte String)

If you use Composer, these dependencies should be handled automatically.

## Getting Started

Simple usage looks like:

```php
use sypher\keez\KeezSDK;

$keez = new KeezSDK([
   "client_id" => "a1b2c3d4e5f6g7h8i9j10k11l12m13n1",
   "app_id"    => "vendor-abcdefghijklmnopqrtuvxz",
   "secret"    => "abcdefghijklmnopqrstuvxzw"
 ], $devmode);
```

Use devmode true for staging environment and false for the production environment.

Several entities are used to work this Keez. These entities contain all properties included in the documentation.

- [Article](https://app.keez.ro/help/api/data_models_item.html)
- [Invoice](https://app.keez.ro/help/api/data_models_invoice_header.html)
- [InvoiceLineItem](https://app.keez.ro/help/api/data_models_invoice_details.html)
- [Partner](https://app.keez.ro/help/api/data_models_legal_partner.html)
- [Person](https://app.keez.ro/help/api/data_models_nonlegal_partner.html)

To get the response in case of an error, use function getLastError. This returns a string with the response, which 
normally is a JSON.

```php
$error = $keez->getLastError();
```

## Functions index

#### Articles
- [createArticle($Article)](#createarticle)
- [getArticle($articleId)](#getarticle)
- [updateArticle($Article)](#updatearticle)
- [getArticles($filter, $order, $count, $offset)](#getarticles)

#### Invoices
- [createInvoice($Invoice)](#createinvoice)
- [updateInvoice($Invoice)](#updateinvoice) 
- [getInvoice($invoiceId)](#getinvoice)
- [getInvoices($filter, $order, $count, $offset)](#getinvoices)
- [deleteInvoice($invoiceId)](#deleteinvoice)
- [validateInvoice($invoiceId)](#validateinvoice)
- [eFacturaInvoice($invoiceId)](#efacturainvoice)

---
## Functions 
### createArticle

- createArticle - Receives an entity of type `Article` as input and returns an entity of the
same type `Article` on success, or false on failure. The returned entity will contain all full properties of the object,
including properties which were not set on the input article, plus the 
`externalId`.

```php
use sypher\keez\entity\Article;

$article = new Article();
$article->name = "Sample Product";
$article->code = "SKY_SAMPLE_PROD";
$article->currencyCode = "RON";
$article->measureUnitId = 1;
$article->categoryExternalId = "MISCSRV";
$article->isActive = true;

$this->keez->createArticle($article);
```

### getArticle

- getArticle - Receives the externalId of an `Article` and returns an entity of type `Article`
on success or false on failure.

```php
$article = $keez->getArticle("articleId");
```

### updateArticle

- updateArticle - Receives as input an entity of type `Article` with full or partial 
properties and returns an entity of the type `Article` on success, with all full properties of the object, or false on
failure. If partial properties are sent, only those properties will be updated. In all cases, externalId must be
included in the input object.

Partial update:
```php
$article = new stdClass();
$article->externalId = "abcdefg";
$article->name = "Sample Product Xtra Large";
$article = $keez->updateArticle($article);
```

Full update:
```php
$article = $keez->getArticle("articleId");
$article->name = "Sample Product Xtra Large";
$article = $keez->updateArticle($article);
```

### getArticles

- getArticles(`$filter`, `$order`, `$count`, `$offset`) - Performs a search for Articles with 
specific filters, in the given `order`. Returns an array of `Article` objects, or `false` on failure.
All parameters are optional. See [Working with filters](#working-with-filters) for an overview of how to pass filters.

```php
$keezProducts = $keez->getArticles();
```

### createInvoice

- createInvoice - Receives an entity of type `Invoice` as input and returns an entity of the
same type `Invoice` on success, or false on failure. The returned entity will contain all full properties of the object,
including properties which were not set on the input article, plus the `externalId`.

```php
$savedInvoice = $this->keez->createInvoice($invoice);
```

### updateInvoice

- updateInvoice(`$invoice`) - Receives an entity of type `Invoice` as input and returns an entity of the
  same type `Invoice` on success, or false on failure. The returned entity will contain all full properties of the object,
  including properties which were not set on the input article.

```php
$updatedInvoice = $this->keez->updateInvoice($invoice);
```

### getInvoice

- getInvoice(`$invoiceId`) - Receives the externalId of an `Invoice` and returns an entity of
type `Invoice`, or false on failure.

```php
$invoice = $keez->getInvoice("3f066d8a330a4313ad02bdfd537d2c79");
```

### getInvoices

- getInvoices(`$filter`, `$order`, `$count`, `$offset`) - Performs a search for invoices with
specific filters, in the given order. Returns an array of `Invoice` objects, or false on failure. 
All parameters are optional. See [Working with filters](#working-with-filters) for an overview of how to pass filters.

### deleteInvoice

- deleteInvoice(`$invoiceId`) - Receives the externalId of an `Invoice` and returns true on 
success or false on failure.

```php
$keez->deleteInvoice("3f066d8a330a4313ad02bdfd537d2c79");
```

### validateInvoice

- validateInvoice(`$invoiceId`) - Receives the externalId of an `Invoice` and returns true
on success or false on failure.

```php
$keez->validateInvoice("3f066d8a330a4313ad02bdfd537d2c79");
```

### eFacturaInvoice
- eFacturaInvoice(`$invoiceId`) - Receives the externalId of an `Invoice` and returns true
  on success or false on failure.

```php
$keez->eFacturaInvoice("3f066d8a330a4313ad02bdfd537d2c79");
```

## Working with filters

`filter` can be either an array with the conditions or a string with the exact syntax supported by Keez API.

If sending the filter as an array, the array values can be arrays with two or three items. Two items assumes the
operation is equal.

- ['searchForVariable', 'operation', 'searchForValue']
- ['searchForVariable', 'searchForValue']

Supported operations are all comparisons like =, !=, <=, >=, <, > and special operations like %% (equivalent of SQL
LIKE) and % (string starts with).

Example:

```php
// searching for an Invoice with a specific serie and number, using array
$filter = [["series", "=", "SERIE"], ["number", 106]];
// searching for an Invoice with a specific serie and number, using string
$filter = "series[eq]:SERIE AND number[eq]:106";
// both versions have the same effect

$invoice = $keez->getInvoices($filter);

// searching for a specific Article by code
$filter = [["code", 'MY_SKU']];
$articles = $keez->getArticles($filter);
```


## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.