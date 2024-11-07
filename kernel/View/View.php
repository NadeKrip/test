<?php

namespace App\View;

class View
{
    private array $extractedElements = [];

    public function page(string $fileName, string $directory, array $extract = []){
        $filePath = APP_PATH.'/src/View/'.$directory.$fileName.'.php';

        if (!file_exists($filePath)) {
            return false;
        }
        foreach ($this->extractedElements as $key => $element){
            extract([$key => $element]);
        }
        require_once $filePath;
    }

    public function addExtractList($extractedData)
    {
        $this->extractedElements = array_merge($this->extractedElements, $extractedData);
    }
}