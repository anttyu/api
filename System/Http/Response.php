<?php

namespace Http;

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

    public function setHeader($header)
    {
        $this->headers[] = $header;
    }

    public function setContent($content)
    {
        $this->content = json_encode($content);
    }

    public function sendStatus($code)
    {
        $this->setStatusCode($code);
        $this->setHeader('HTTP/1.1 ' . $code . ' ' . $this->getStatusCodeText($code));
    }

    public function render()
    {
        http_response_code($this->statusCode);

        foreach ($this->headers as $header) {
            header($header);
        }

        echo $this->content;
    }

    protected function getStatusCodeText($code)
    {

        $statusTexts = [
        ];

        return isset($statusTexts[$code]) ? $statusTexts[$code] : 'unknown status';
    }
}
