<?php

namespace Core;

use Intervention\Image\Interfaces\EncodedImageInterface;

class TransformedImage {
    public function __construct(public string $imageName, public EncodedImageInterface $encodedImage){}

    public function getBinaryString(){
        return $this->encodedImage->__toString();
    }
}