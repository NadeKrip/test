<?php

namespace App\Http;

class Request implements \App\Http\RequestInterface
{
    public function __construct(
        public readonly array $get,
        public readonly array $post,
        public readonly array $server,
        public readonly array $files,
        public readonly array $cookie
    )
    {
    }

    public static function createFromGlobals(): static{
        return new static($_GET, $_POST, $_SERVER, $_FILES, $_COOKIE);
    }

    public function uri(): string{
        return strtok($this->server['REQUEST_URI'], '?');
    }

    public function method(): string{
        return $this->server['REQUEST_METHOD'];
    }

    public function requestUri(): string
    {
        return $this->server['REQUEST_URI'];
    }

    public function isGET(): bool
    {
        return count($this->get) > 0;
    }

    public function input(string $key, $default = null)
    {
        return $this->post[$key] ?? $this->get[$key] ?? $default;
    }
}