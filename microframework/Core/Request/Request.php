<?php

namespace MicroFramework\Core\Request;

class Request
{
    public function getFirst(string $key)
    {
        if (!is_null($this->get($key)))
            return $this->get($key);

        if (!is_null($this->post($key)))
            return $this->post($key);

        if (!is_null($this->stream($key)))
            return $this->stream($key);

        return null;
    }

    public function getAll(string $key)
    {
        $temp = [];
        if (!is_null($this->get($key)))
            $temp["GET"] = $this->get($key);

        if (!is_null($this->post($key)))
            $temp["POST"] = $this->post($key);

        if (!is_null($this->stream($key)))
            $temp["STREAM"] = $this->stream($key);

        return $temp;
    }

    public function get(?string $key = null): string|array|null
    {
        if (!$key)
            return $_GET;

        if (isset($_GET[$key]))
            return $_GET[$key];

        return null;
    }

    public function post(?string $key = null): string|array|null
    {
        if (!$key)
            return $_POST;

        if (isset($_POST[$key]))
            return $_POST[$key];

        return null;
    }

    public function stream(?string $key = null,  bool $json = false)
    {
        $request = file_get_contents("php://input");

        if (!is_null($key))
        {
            $request = json_decode($request, true);
            if ($request)
                return $request[$key];
            else
                return null;
        }
        if ($json)
            return json_decode($request, true);
        else
            return $request;

    }
}