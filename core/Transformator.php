<?php

namespace Core;

use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\EncodedImageInterface;
use Utils\Functions;

class Transformator {

    private string $uniqueName;
    private ImageManager $imageManager;
    private ?TransformedImage $imageTransformed =  null;
    
    /**
     * __construct Initializes a transformator with the options to apply in its transformations.
     *
     * @param  string $tmpPath Path to the image file to transform.
     * @param  string $clientName Original name of the image file.
     * @param  int $quality Quality to apply in the transformations.
     * @param  bool $keepOriginalName Flag to indicate if the name of the result 
     *  should persists with the `$clientName` given.
     * @return void
     */
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

        $this->setTransformedImage($imageTransformed, 'webp');

        return $this;
    }

    public function toPng(): static{
        $imageTransformed = $this->readImage()->toPng();

        $this->setTransformedImage($imageTransformed, 'png');
        return $this;
    }

    private function setTransformedImage(EncodedImageInterface $encodedImage, string $extension){
        $this->setNameExtension($extension);
        $this->imageTransformed = new TransformedImage($this->uniqueName, $encodedImage);
    }
    
    /**
     *  Sets the name with the extension given.
     *
     * @param  string $extension A valid extension to the file.
     * @return void
     */
    private function setNameExtension(string $extension) {
        $this->uniqueName = $this->uniqueName.".$extension";
    }
    
    /**
     *  Returns the current state of imageTransformed property.
     *
     * @return \Core\TransformedImage | null
     */
    public function getTransformedImage(){
        return $this->imageTransformed;
    }
}