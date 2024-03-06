<?php

namespace sypher\keez;

use sypher\keez\entity\Article;
use sypher\keez\entity\Invoice;

class KeezSDK
{
    protected $access_token = false;
    protected $access_token_exp = 0;

    protected $client_id = "";
    protected $app_id = "";
    protected $secret = "";

    protected $api_client;
    protected $devmode = false;

    protected $lastError = "";

    protected $apiHosts = [
        "dev" => "staging.keez.ro",
        "prod" => "app.keez.ro"
    ];

    public function __construct($credentials, $devmode = false)
    {
        $this->client_id = $credentials['client_id'];
        $this->app_id = $credentials['app_id'];
        $this->secret = $credentials['secret'];

        $this->setDevmode($devmode);

        $this->api_client = new APIClient();
    }

    public function generateToken()
    {
        $this->access_token = false;

        $payload = [
            'client_id' => "app" . $this->app_id,
            'client_secret' => $this->secret,
            'grant_type' => 'client_credentials',
            'scope' => "public-api",
        ];

        $payload = http_build_query($payload);

        $headers = [
            'Content-Type: application/x-www-form-urlencoded'
        ];

        $endPoint = "https://" . $this->getHost() . "/idp/connect/token";

        $result = $this->api_client->callAPI("POST", $endPoint, $headers, $payload);

        if (!$this->api_client->getError() && ($this->api_client->getExtendedInfo()['http_code'] == 200)) {
            $result = json_decode($result);
            $this->access_token = $result->access_token;
            $this->access_token_exp = $result->expires_in;
        } else {
            throw new \Exception($this->api_client->getError() . "Service response code: " . $this->api_client->getExtendedInfo()['http_code'] );
        }

        return $this->access_token;
    }

    /**
     * https://app.keez.ro/help/api/item_lists.html
     *
     * @return array|false
     */
    public function getArticles()
    {
        if (!$this->access_token) {
            $this->generateToken();
        }

        $endPoint = $this->getAPIUrl() . "/" . $this->client_id . "/items";

        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->access_token
        ];

        $result = $this->api_client->callAPI("GET", $endPoint, $headers);
        if ($this->api_client->getExtendedInfo()['http_code'] == 200) {
            $retval = [];
            $results = json_decode($result, true);
            foreach ($results as $item) {
                $retval[] = HydrateObjects::hydrate(new Article($item["externalId"]), $item);
            }
            return $retval;
        } else {
            $this->setLastError($result);
            return false;
        }
    }

    /**
     * https://app.keez.ro/help/api/item_list.html
     *
     * @param $externalId
     * @return false|Article
     */
    public function getArticle($externalId)
    {
        $result = $this->call("/items/$externalId", "GET");

        if ($this->api_client->getExtendedInfo()['http_code'] == 200) {
            $result = json_decode($result, true);
            return HydrateObjects::hydrate(new Article($externalId), $result);
        } else {
            $this->setLastError(json_encode($result));
            return false;
        }
    }

    /**
     * https://app.keez.ro/help/api/item_create.html
     *
     * @param $payload
     * @return false|Article
     */
    public function createArticle($payload)
    {
        $payload = json_encode($payload);

        $result = $this->call("/items", "POST", $payload);

        if ($this->api_client->getExtendedInfo()['http_code'] < 300) {
            $result = json_decode($result);
            return $this->getArticle($result->externalId);
        } else {
            $this->setLastError($result);
            return false;
        }
    }

    /**
     * https://app.keez.ro/help/api/item_patch.html
     *
     * @param Article $article
     * @return false|Article
     */
    public function updateArticle(Article $article)
    {
        $payload = json_encode($article);

        $result = $this->call("/items/$article->externalId", "PATCH", $payload);

        if ($this->api_client->getExtendedInfo()['http_code'] < 300) {
            return $this->getArticle($article->externalId);
        } else {
            $this->setLastError($result);
            return false;
        }
    }

    /**
     * https://app.keez.ro/help/api/invoice_create.html
     *
     * @param $payload
     * @return false|Invoice
     */
    public function createInvoice($payload)
    {
        $payload = json_encode($payload);

        $result = $this->call("/invoices", "POST", $payload);

        if ($this->api_client->getExtendedInfo()['http_code'] < 300) {
            $result = json_decode($result);
            return $this->getInvoice($result->externalId);
        } else {
            $this->setLastError($result);
            return false;
        }
    }

    /**
     * https://app.keez.ro/help/api/invoice_update.html
     *
     * @param Invoice $invoice
     * @return false|Invoice
     */
    public function updateInvoice(Invoice $invoice)
    {
        $payload = json_encode($invoice);

        $result = $this->call("/invoices/$invoice->externalId", "PUT", $payload);

        if ($this->api_client->getExtendedInfo()['http_code'] < 300) {
            return $this->getArticle($invoice->externalId);
        } else {
            $this->setLastError($result);
            return false;
        }
    }

    /**
     * https://app.keez.ro/help/api/invoice_list.html
     *
     * @param $externalId
     * @return false|Invoice
     */
    public function getInvoice($externalId)
    {
        $result = $this->call("/invoices/$externalId", "GET");

        if ($this->api_client->getExtendedInfo()['http_code'] == 200) {
            $result = json_decode($result, true);
            return HydrateObjects::hydrate(new Invoice($externalId), $result);
        } else {
            $this->setLastError(json_encode($result));
            return false;
        }
    }

    public function getInvoices($filter = "", $order = "", $count = "", $offset = "")
    {
        $operators = [
            "="  => "eq",
            "!=" => "neq",
            "<"  => "lt",
            ">"  => "gt",
            ">=" => "gte",
            "<=" => "lte",
            "%%" => "like",
            "%"  => "sw"
        ];

        $buildFilter = "";

        if (is_array($filter) && in_array(count($filter), [2, 3])) {
            $i = 0;
            foreach($filter as $param) {
                if (count($param) == 2) {
                    $operator = "=";
                    $value = $param[1];
                } else {
                    $operator = $param[1];
                    $value = $param[2];
                }

                $buildFilter .= $param[0] . "[" . $operators[$operator] . "]:" . $value;

                $i++;
                if ($i < count($filter)) {
                    $buildFilter .= " AND ";
                }
            }
        } else {
            $buildFilter = $filter;
        }

        $buildFilter = urlencode($buildFilter);
        $result = $this->call("/invoices?filter=$buildFilter&order=$order&count=$count&offset=$offset", "GET");

        if ($this->api_client->getExtendedInfo()['http_code'] != 200) {
            $this->setLastError(json_encode($result));
            return false;
        }

        $retval = [];

        if ($result) {
            $json = json_decode($result, true);
            $data = $json["data"];
            foreach($data as $_invoice) {
                $retval[] = HydrateObjects::hydrate(new Invoice($_invoice["externalId"]), $_invoice);
            }

            return $retval;
        } else {
            return false;
        }
    }

    /**
     * https://app.keez.ro/help/api/invoice_delete.html
     *
     * @param $externalId
     * @return bool
     */
    public function deleteInvoice($externalId): bool
    {
        $payload = json_encode(
            ["externalId" => $externalId]
        );

        $result = $this->call("/invoices", "DELETE", $payload);

        if ($this->api_client->getExtendedInfo()['http_code'] < 300) {
            return true;
        } else {
            $this->setLastError($result);
            return false;
        }
    }

    /**
     * https://app.keez.ro/help/api/invoice_validate.html
     *
     * @param $externalId
     * @return bool
     */
    public function validateInvoice($externalId): bool
    {
        $payload = json_encode(
            ["externalId" => $externalId]
        );

        $result = $this->call("/invoices/valid", "POST", $payload);

        if ($this->api_client->getExtendedInfo()['http_code'] < 300) {
            return true;
        } else {
            $this->setLastError($result);
            return false;
        }
    }

    /**
     * https://app.keez.ro/help/api/invoice_download_pdf.html
     *
     * @param $externalId
     * @return bool|string
     */
    public function downloadInvoice($externalId)
    {
        $result = $this->call("/invoices/$externalId/pdf", "GET");

        if ($this->api_client->getExtendedInfo()['http_code'] < 300) {
            return $result;
        } else {
            $this->setLastError($result);
            return false;
        }
    }

    /**
     * https://app.keez.ro/help/api/invoice_submit_efactura.html
     *
     * @param $externalId
     * @return bool
     */
    public function eFacturaInvoice($externalId): bool
    {
        $payload = json_encode(
            ["externalId" => $externalId]
        );

        $result = $this->call("/invoices/efactura/submitted", "POST", $payload);

        if ($this->api_client->getExtendedInfo()['http_code'] < 300) {
            return true;
        } else {
            $this->setLastError($result);
            return false;
        }
    }

    private function call($path, $method, $payload = "")
    {
        if (!$this->access_token) {
            $this->generateToken();
        }

        $endPoint = $this->getAPIUrl() . "/" . $this->client_id . $path;
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->access_token
        ];

        return $this->api_client->callAPI($method, $endPoint, $headers, $payload);
    }

    /**
     * @return bool
     */
    public function isDevmode(): bool
    {
        return $this->devmode;
    }

    /**
     * @param bool $devmode
     * @return keezSDK
     */
    public function setDevmode(bool $devmode): keezSDK
    {
        $this->devmode = $devmode;
        return $this;
    }

    public function getAPIUrl()
    {
        return("https://" . $this->getHost() . "/api/v1.0/public-api");
    }

    private function getHost()
    {
        if ($this->isDevmode()) {
            $host = $this->apiHosts["dev"];
        } else {
            $host = $this->apiHosts["prod"];
        }

        return $host;
    }

    /**
     * @return string
     */
    public function getLastError(): string
    {
        return $this->lastError;
    }

    /**
     * @param string $error
     * @return KeezSDK
     */
    public function setLastError(string $error): KeezSDK
    {
        $this->lastError = $error;
        return $this;
    }
}
