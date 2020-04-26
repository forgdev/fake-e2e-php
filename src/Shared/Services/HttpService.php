<?php

namespace Shared\Services;

use Config\CurlConfig;
use Exception;

class HttpService
{

    private CurlConfig $curlConfig;

    public function __construct(CurlConfig $curlConfig)
    {
        $this->curlConfig = $curlConfig;
    }

    /**
     * @param string $url
     * @param array $request
     * @param array $headers
     * @return mixed
     * @throws Exception
     */
    public function curlPost(string $url, array $request, array $headers = [])
    {
        $query = json_encode($request);

        $response = $this->sendRequest($url,
            $query,
            array_merge([
                'Content-Type: application/json',
                'Content-Length: ' . strlen($query),
            ],
                $headers));

        return json_decode($response, true);
    }

    /**
     * @param string $url
     * @param string $request
     * @param array $headers
     * @return bool|string
     * @throws Exception
     */
    private function sendRequest(string $url, string $request, array $headers)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, $this->curlConfig['freshConnect']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, $this->curlConfig['returnTransfer']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $this->curlConfig['verifySSLHost']);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->curlConfig['verifySSLPeer']);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->curlConfig['connectTimeout']);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->curlConfig['timeout']);
        curl_setopt($ch, CURLOPT_POST, $this->curlConfig['post']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);

        $response = curl_exec($ch);

        if (!$response) {
            throw new Exception('Empty response from third party service');
        }

        curl_close($ch);

        return $response;
    }

}