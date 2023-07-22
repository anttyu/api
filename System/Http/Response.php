<?php

namespace System\Http;

class Response
{
    protected $headers = [];
    protected $statusCode = 200;
    protected $content;

    public function setStatusCode($code)
    {
        $this->statusCode = $code;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function setContent($content)
    {
        $this->content = json_encode($content);
    }

}
