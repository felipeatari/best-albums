<?php

namespace App\Http;

class Request
{
    private $uri;
    private $methodHTTP;

    public function __construct()
    {
        $this->uri = $_GET['url'] ?? '';
        $this->methodHTTP = $_SERVER['REQUEST_METHOD'] ?? '';
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getMethodHTTP()
    {
        return $this->methodHTTP;
    }
}
