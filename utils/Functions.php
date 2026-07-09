<?php

namespace Utils;

use Error;

class Functions {
    static function getNameWithoutFormat(string $fileName): string{
        return explode('.', $fileName)[0];
    }

    static function debug($input) {
        echo '<pre>';
        var_dump($input);
        echo '</pre>';
        exit;
    }

    static function downloadTemporaryFileToClient(string $path, string $mimeType, string $fileName) {
        if(!file_exists($path)) 
            throw new Error("Can't download a file that doesn't exists");


        header("Content-Type: $mimeType");
        header("Content-Disposition: attachment; filename=$fileName");
        header('Content-Length: ' . filesize($path));
        header('Pragma: public');
        header('Cache-Control: must-revalidate');

        readfile($path);

        unlink($path);

        exit;
    }
}