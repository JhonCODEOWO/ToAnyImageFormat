<?php

namespace Core;

use Functions;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\EncodedImageInterface;
use Intervention\Image\Interfaces\ImageInterface;

class Transformator {

    private string $uniqueName;
    private ImageManager $imageManager;
    private ?TransformedImage $imageTransformed =  null;

    public function __construct(private string $tmpPath, string $clientName,private ?int $quality = 80,?bool $keepOriginalName = false)
    {
        $this->tmpPath = $tmpPath;
        $this->uniqueName = (!$keepOriginalName)? uniqid(): Functions::getNameWithoutFormat($clientName) ;
        $this->imageManager = new ImageManager(new Driver());
        $this->quality = $quality;
    }

    private function readImage() {
        return $this->imageManager->read($this->tmpPath);
    }

    public function toWebp(): static{
        $imageTransformed = $this->readImage()->toWebp($this->quality);

        $this->setTransformedImage($imageTransformed);

        return $this;
    }

    public function toPng(): static{
        $imageTransformed = $this->readImage()->toPng($this->quality);

        $this->setTransformedImage($imageTransformed);
        return $this;
    }

    private function setTransformedImage(EncodedImageInterface $encodedImage){
        $this->imageTransformed = new TransformedImage($this->uniqueName, $encodedImage);
    }

    public function getTransformedImage(){
        return $this->imageTransformed;
    }
}