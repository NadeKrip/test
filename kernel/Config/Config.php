<?php

namespace App\Config;

use App\Config\ConfigInterface;

class Config implements ConfigInterface
{
    public function get(string $key, $default = null)
    {
        [$file, $key] = explode('.', $key);

        $configPath =  APP_PATH."/configs/$file.php";

        if (! file_exists($configPath)) {
            return $default;
        }

        $config = require $configPath;

        return $config[$key] ?? $default;
    }
}