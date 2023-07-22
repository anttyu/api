<?php

namespace Http;

class Request
{
    public function get($key = null)
    {
        if ($key !== null) {
            return $_GET[$key] ?? null;
        }

        return $_GET;
    }

    public function post($key = null)
    {
        if ($key !== null) {
            return $_POST[$key] ?? null;
        }

        return $_POST;
    }

    public function input($key = null)
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if ($key !== null) {
            return $data[$key] ?? null;
        }

        return $data;
    }

    public function server($key = null)
    {
        if ($key !== null) {
            return $_SERVER[strtoupper($key)] ?? null;
        }

        return $_SERVER;
    }

    public function getMethod()
    {
        return strtoupper($this->server('REQUEST_METHOD'));
    }

    public function getClientIp()
    {
        return $this->server('REMOTE_ADDR');
    }

    public function getUrl()
    {
        return $this->server('REQUEST_URI');
    }
}
