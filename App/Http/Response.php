<?php

namespace App\Http;

class Response
{
    private $statusCode;
    private $content;

    public function __construct($statusCode = 200, $content)
    {
        $this->statusCode = $statusCode;
        $this->content = $content;
    }

    private function getStatusCode()
    {
        return  http_response_code($this->statusCode);
    }

    private function getContent()
    {
        return  $this->content;
    }

    public function response()
    {
        $this->getStatusCode();
        return $this->getContent();
    }
}
