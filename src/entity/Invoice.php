<?php

namespace sypher\keez\entity;

class Invoice
{
    public $externalId;
    public $series;
    public $number;
    public $documentDate;
    public $dueDate;
    public $vatOnCollection;
    public $referenceCurrencyCode;
    public $currencyCode;
    public $exchangeRate;
    public $paymentTypeId;
    public $originalNetAmount;
    public $originalVatAmount;
    public $netAmount;
    public $vatAmount;
    public $grossAmount;
    public $remainingAmount;
    public $partner;
    public $status;
    public $invoiceDetails = [];

    public const PAYMENT_TYPES = [
        "BFCash"               => 1,    // Bon fiscal platit cu numerar
        "BFCard"               => 2,    // Bon fiscal platit cu cardul
        "Bank"                 => 3,    // Transfer bancar
        "ChitCash"             => 4,    // Plata numerar cu chitanta
        "Ramburs"              => 5,    // Ramburs
        "ProcesatorPlati"      => 6,    // Procesatori plati (PayU, Netopia, euplatesc)
        "PlatformaDistributie" => 7,    // Platforme distributie si plata (Emag)
        "VoucherVacantaCard"   => 8,    // Voucher de Vacanta - Card
        "VoucherVacantaTichet" => 9,    // Voucher de Vacanta - Tichet
    ];

    public const MEASURE_UNITS = [
        "Buc"  => 1,    // Bucata
        "Luna" => 2,    // Luna om
        "An"    => 3,   // An
        "Zi"    => 4,   // Ziua
        "Ora"   => 5,   // Ora
        "Kg"    => 6,   // Kilogram
        "Km"    => 7,   // Kilometrru
        "KWh"   => 8,   // Kilowatt ora
        "KW"    => 9,   // Kilowatt
        "M"     => 10,  // Metru
        "L"     => 11,  // Litru
        "Min"   => 12,  // Minut
        "Luna"  => 13,  // Luna
        "Mp"    => 14,  // Metru patrat
        "Oz"    => 15,  // Uncie de lichid (usa)
        "Per"   => 16,  // Pereche,
        "Trim"  => 17,  // Trimestru (de un an),
        "T"     => 18,  // Tona (tonametrica)
        "Sapt"  => 19,  // Saptamana
        "Mc"    => 20,  // Metru cub
        "Cutie" => 22,  // Cutie
        "Pag"   => 23,  // Pagina
        "Rola"  => 24,  // Rola
        "Coala" => 25,  // Coala
        "Tambur" => 26, // Tambur
        "Set"   => 27   // Set
    ];

    /**
     * @param string $externalId
     */
    public function __construct(string $externalId = "")
    {
        $this->externalId = $externalId;
    }
}
