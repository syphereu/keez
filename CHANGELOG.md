# Changelog

## 0.2.0 - 2024-03-06

- added updateInvoice function
- added getInvoices function
- added eFacturaInvoice function
- added additional properties for Invoice entity
- hydration for invoice checks for partner and invoiceDetails fields to work seamslessly with getInvoices, which returns a stripped version of an invoice, like a summary
- generateToken error handling improved for non 200 responses

## 0.1.0 - 2024-03-04

- Added support for Composer
- General SDK framework
- Implemented createArticle($Article) function
- Implemented getArticle($articleId) function
- Implemented updateArticle($Article) function
- Implemented getArticles() function
- Implemented createInvoice($Invoice) function
- Implemented getInvoice($invoiceId) function
- Implemented deleteInvoice($invoiceId) function
- Implemented validateInvoice($invoiceId)
- Added entities: Article, Invoice, InvoiceLineItem, Partner, Person
