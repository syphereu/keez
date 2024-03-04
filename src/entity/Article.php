<?php

namespace sypher\keez\entity;

class Article
{
    public $externalId;
    public $name;
    public $code;
    public $categoryExternalId;
    public $currencyCode;
    public $isActive;
    public $measureUnitId;
    public $lastPrice;
    public $hasExcise;
    public $exciseValue;

    public function __construct($externalId = "")
    {
        $this->externalId = $externalId;
    }
}
