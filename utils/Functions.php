<?php

class Functions {
    static function getNameWithoutFormat(string $fileName): string{
        return explode('.', $fileName)[0];
    }
}