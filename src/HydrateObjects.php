<?php

namespace Sypher\Keez;

use sypher\keez\entity\Article;
use sypher\keez\entity\Invoice;
use sypher\keez\entity\InvoiceLineItem;
use sypher\keez\entity\Partner;

class HydrateObjects
{

    public static function hydrate($newObject, $object)
    {
        $type = get_class($newObject);
        switch ($type) {
            case Article::class:
                return self::hydrateArticle($newObject, $object);
            case Invoice::class:
                return self::hydrateInvoice($newObject, $object);
        }

        return false;
    }

    public static function hydrateArticle(Article $newObject, $object): Article
    {
        return self::fillObject($newObject, $object);
    }

    public static function hydrateInvoice(Invoice $newObject, $object): Invoice
    {
        $newObject = self::fillObject($newObject, $object);

        if(isset($object["partner"])) {
            $newObject->partner = self::fillObject(new Partner(), $object["partner"]);
        }

        if (isset($object["invoiceDetails"])) {
            $invoiceDetails = [];
            foreach($object["invoiceDetails"] as $line) {
                $invoiceDetails[] = self::fillObject(new InvoiceLineItem(), $line);
            }
            $newObject->invoiceDetails = $invoiceDetails;
        }

        return $newObject;
    }

    public static function fillObject($object, array $properties)
    {
        foreach ($properties as $key => $value) {
            $object->$key = $value;
        }

        return $object;
    }

}
