<?php

namespace sypher\keez\entity;

class InvoiceLineItem
{
    public $itemExternalId;
    public $itemName;
    public $itemDescription;
    public $measureUnitId;
    public $quantity;
    public $unitPrice;
    public $vatPercent;
    public $discountType;
    public $discountPercent;
    public $discountValueOnNet;
    public $originalNetAmount;
    public $originalVatAmount;
    public $discountNetValue;
    public $discountGrossValue;
    public $discountVatValue;
    public $netAmount;
    public $vatAmount;
    public $grossAmount;
    public $exciseAmount;
    public $originalNetAmountCurrency;
    public $originalVatAmountCurrency;
    public $discountNetValueCurrency;
    public $discountGrossValueCurrency;
    public $discountVatValueCurrency;
    public $netAmountCurrency;
    public $vatAmountCurrency;
    public $grossAmountCurrency;
    public $exciseAmountCurrency;
}
