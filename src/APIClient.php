<?php

namespace sypher\keez;

/**
 * Simple REST library
 *
 * @author Cristian Badea <cristian.badea@sypher.eu>
 */

class APIClient
{
    protected $error;
    protected $extended_info;

    /**
     * curl timeouts in seconds, 0 = no limit
     */
    protected $connectTimeout = 5;
    protected $timeout = 30;

    public function callAPI($method, $url, $headers = false, $data = false)
    {
        $curl = curl_init();

        $this->setError(null);

        $default_methods = ["POST", "PUT", "GET"];

        $checkMethod = $method;
        if (!in_array($method, $default_methods)) {
            $checkMethod = "OTHER";
        }

        switch ($checkMethod) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

                if ($data) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                }
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

                if ($data) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                }
                break;
            case "OTHER":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

                if ($data) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                }
                break;

            default:
                if (is_array($headers)) {
                    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                }

                if ($data) {
                    $url = sprintf("%s?%s", $url, http_build_query($data));
                }
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->connectTimeout);
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);

        $result = curl_exec($curl);

        if (curl_errno($curl)) {
            $this->setError(curl_error($curl));
        }

        $this->setExtendedInfo(curl_getinfo($curl));
        curl_close($curl);

        return $result;
    }

    /**
     * @param int $connectTimeout seconds to wait for the connection, 0 = no limit
     * @param int $timeout seconds for the whole request, 0 = no limit
     * @return APIClient
     */
    public function setTimeouts(int $connectTimeout, int $timeout)
    {
        $this->connectTimeout = $connectTimeout;
        $this->timeout = $timeout;
        return $this;
    }

    private function setError($error)
    {
        $this->error = $error;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @return mixed
     */
    public function getExtendedInfo()
    {
        return $this->extended_info;
    }

    /**
     * @param mixed $extended_info
     * @return APIClient
     */
    private function setExtendedInfo($extended_info)
    {
        $this->extended_info = $extended_info;
        return $this;
    }
}
