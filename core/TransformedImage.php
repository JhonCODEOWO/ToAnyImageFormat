<?php

namespace Core;

use Intervention\Image\Interfaces\EncodedImageInterface;

class TransformedImage {
    public function __construct(public string $imageName, EncodedImageInterface $encodedImage){}
}