<?php

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
        <input type="file" name="images[]" id="images" multiple>
        <button type="submit">Convertir imagen(es)</button>
    </form>
</body>
</html>