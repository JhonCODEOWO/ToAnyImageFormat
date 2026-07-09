<?php

require __DIR__ . '/../vendor/autoload.php';
$formats = require __DIR__ . '/../config/AvailableFormats.php';

use Core\Transformator;
use Core\TransformedImage;
use Core\UploadedFile;
use Core\ZipArchiveBuilder;
use Utils\Functions;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $body = $_POST;
    $quality = filter_var($body['quality'], FILTER_VALIDATE_INT);
    $formatToConvert = filter_var($body['wanted_format'], FILTER_SANITIZE_SPECIAL_CHARS);
    $keepNames = isset($body['keep_names']);

    if(!$quality) $errors[] = "Type the quality needed for your conversions";

    if($quality < 1 || $quality > 100) $errors[] = 'Type the quality between 1 or 100 percent value.';

    if(!in_array($formatToConvert, $formats) || !$formatToConvert) $errors[] = 'Please, select a valid format to convert';

    if($_FILES['images']['error'][0] === UPLOAD_ERR_NO_FILE) $errors[] = "You should select almost one file to continue.";

    if(empty($errors)){
        $transformatorMainFunction = "to".ucfirst($formatToConvert);

        $uploadedFiles = [];
        $transformedFiles = [];
        //Prepare all uploaded files in a fresh array with UploadedFile classes
        foreach ($_FILES['images']['name'] as $index => $clientName) {
            $uploadedFiles[] = new UploadedFile(
                $clientName,
                $_FILES['images']['tmp_name'][$index]
            );
        }

        //Execute transformations.
        foreach ($uploadedFiles as $uploadedFile) {
            $transformator = new Transformator(
                    $uploadedFile->tempPath, 
                    $uploadedFile->clientName, 
                    $quality, 
                    $keepNames
                );

            if(!method_exists($transformator, $transformatorMainFunction)) continue;

            $transformedFiles[] = $transformator->$transformatorMainFunction()->getTransformedImage();
        }

        $zipArray = array_map(function (TransformedImage $transformedImage) {
            return [
                "name" => $transformedImage->imageName,
                "binary" => $transformedImage->getBinaryString()
            ];
        }, $transformedFiles);

        $builder = new ZipArchiveBuilder($zipArray);
        $zipPath = $builder->build();

        //Download the zip file
        Functions::downloadTemporaryFileToClient(
            $zipPath, 
            'application/zip', 
            $builder->zipName
        );
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Convert images to any type</title>
</head>
<body>
    <h1>A simple image converter</h1>
    <p>Just select any image that you want to convert and the options to make the operations</p>
    <form action="" method="post" enctype="multipart/form-data">
        <?php foreach ($errors as $error) :?>
            <div>
                <p><?php echo $error ?></p>
            </div>
        <?php endforeach?>
        <div>
            <label for="Imágenes para convertir"></label>
            <input type="file" name="images[]" id="images" multiple>
        </div>
        <div>
            <label for="">Keep original file names</label>
            <input type="checkbox" name="keep_names" id="keep_names">
        </div>
        <div>
            <label for="wanted_format">Select a format to convert:</label>
            <select name="wanted_format" id="wanted_format">
                <option value="" selected>Selecciona alguna</option>
                <option value="webp" selected>Webp</option>
                <option value="png" selected>Png</option>
            </select>
        </div>
        <div>
            <label for="quality">Quality for the conversion</label>
            <input type="number" name="quality" id="quality" max="100" min="1" value="70">
        </div>
        <button type="submit">Convertir imagen(es)</button>
    </form>
</body>
</html>