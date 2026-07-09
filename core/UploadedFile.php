<?php

namespace Core;

class UploadedFile {
    public function __construct(public string $clientName, public string $tempPath)
    {}
}