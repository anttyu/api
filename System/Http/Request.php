<?php

namespace System\Http;

/**
 * Class Request
 * @package System\Http
 */
class Request
{
    /**
     * @param null $key
     * @return array|mixed|null
     */
    public function get($key = null)
    {
        if ($key !== null) {
            return $_GET[$key] ?? null;
        }

        return $_GET;
    }

    /**
     * @param null $key
     * @return array|mixed|null
     */
    public function post($key = null)
    {
        if ($key !== null) {
            return $_POST[$key] ?? null;
        }

        return $_POST;
    }

    /**
     * @param null $key
     * @return mixed|null
     */
    public function input($key = null)
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if ($key !== null) {
            return $data[$key] ?? null;
        }
        return $data;

    }

    /**
     * @param null $key
     * @return array|mixed|null
     */
    public function server($key = null)
    {
        if ($key !== null) {
            return $_SERVER[strtoupper($key)] ?? null;
        }

        return $_SERVER;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return strtoupper($this->server('REQUEST_METHOD'));
    }

    /**
     * @return array|mixed|null
     */
    public function getUrl()
    {
        return $this->server('REQUEST_URI');
    }
}
