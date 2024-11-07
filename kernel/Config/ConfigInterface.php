<?php

namespace App\Config;

interface ConfigInterface
{
    public function get(string $key, $default = null);

}