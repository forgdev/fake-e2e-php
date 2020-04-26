<?php

namespace Config;

class CurlConfig
{

    public bool $freshConnect;

    public bool $returnTransfer;

    public bool $verifySSLHost;

    public bool $verifySSLPeer;

    public bool $connectTimeout;

    public int $timeout;

    public bool $post;

    public function __construct(array $config)
    {
        $this->freshConnect = $config['freshConnect'];
        $this->returnTransfer = $config['returnTransfer'];
        $this->verifySSLHost = $config['verifySSLHost'];
        $this->verifySSLPeer = $config['verifySSLPeer'];
        $this->connectTimeout = $config['connectTimeout'];
        $this->timeout = $config['timeout'];
        $this->post = $config['post'];
    }

}