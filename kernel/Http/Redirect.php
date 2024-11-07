<?php

namespace App\Http;

use App\Http\RedirectInterface;

class Redirect implements RedirectInterface
{

    public function to(string $url)
    {
        header("Location: $url");
        exit;
    }
}