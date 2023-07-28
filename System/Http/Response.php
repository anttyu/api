<?php

namespace System\Http;

/**
 * Class Response
 * @package System\Http
 */
class Response
{
    /**
     * @var array
     */
    protected $headers = [];
    /**
     * @var int
     */
    protected $statusCode = 200;
    /**
     * @var
     */
    protected $content;

    /**
     * @param $code
     */
    public function setStatusCode($code)
    {
        $this->statusCode = $code;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param $content
     */
    public function setContent($content)
    {
        $this->content = json_encode($content);
    }

}
