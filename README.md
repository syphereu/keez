# A simple keez.ro library

[![MIT Licensed](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

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

## Implemented functions:

#### Articles
- [createArticle($Article)](#createArticle)
- [getArticle($articleId)](#getArticle)
- [updateArticle($Article)](#updateArticle)
- [getArticles()](#getArticles)

#### Invoices
- [createInvoice($Invoice)](#createInvoice)
- [getInvoice($invoiceId)](#getInvoice)
- [deleteInvoice($invoiceId)](#deleteInvoice)
- [validateInvoice($invoiceId)](#validateInvoice)

---

- <a name="createArticle">createArticle</a> - Receives an entity of type `Article` as input and returns an entity of the
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

- <a name="getArticle">getArticle</a> - Receives the externalId of an `Article` and returns an entity of type `Article`
on success or false on failure.

```php
$article = $keez->getArticle("articleId");
```

- <a name="updateArticle">updateArticle</a> - Receives as input an entity of type `Article` with full or partial 
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

- <a name="getArticles">getArticles</a> - returns an array of entities of type `Article` on success, or `false` on
failure.

```php
$keezProducts = $keez->getArticles();
```

- <a name="createInvoice">createInvoice</a> - Receives an entity of type `Invoice` as input and returns an entity of the
same type `Invoice` on success, or false on failure. The returned entity will contain all full properties of the object,
including properties which were not set on the input article, plus the `externalId`.

```php
$this->keez->createInvoice();
```

- <a name="getInvoice">getInvoice($invoiceId)</a> - Receives the externalId of an `Invoice` and returns an entity of
type `Invoice`, or false on failure.

```php
$invoice = $keez->getInvoice("3f066d8a330a4313ad02bdfd537d2c79");
```

- <a name="deleteInvoice">deleteInvoice($invoiceId)</a> - Receives the externalId of an `Invoice` and returns true on 
success or false on failure.

```php
$keez->deleteInvoice("3f066d8a330a4313ad02bdfd537d2c79");
```

- <a name="validateInvoice">validateInvoice($invoiceId)</a> - Receives the externalId of an `Invoice` and returns true
on success or false on failure.

```php
$keez->validateInvoice("3f066d8a330a4313ad02bdfd537d2c79");
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.