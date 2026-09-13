# Changelog

## 0.3.1 - 2026-09-13

- added curl timeouts for all API calls, defaulting to 5 seconds connect and 30 seconds total (previously no limit)
- added setTimeouts function to KeezSDK and APIClient to override the timeouts (0 = no limit)
- generateToken error message puts the connection error on its own line instead of joining it to the response code
- fixed getLastError returning an empty string (or "false") when a call could not reach Keez; it now returns the connection error
- fixed createArticle, updateArticle, createInvoice, updateInvoice, deleteInvoice, validateInvoice, eFacturaInvoice and downloadInvoice treating a call that could not reach Keez as successful; only 2xx responses are successful now

## 0.3.0 - 2024-09-25

- added support for filtering, ordering, and pagination for getArticles function

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
