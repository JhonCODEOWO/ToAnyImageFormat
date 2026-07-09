<?php

namespace Utils;

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
}