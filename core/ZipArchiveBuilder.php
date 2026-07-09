<?php

namespace Core;

use Error;
use ZipArchive;

class ZipArchiveBuilder {
    private ZipArchive $zipArchive; 
    private mixed $buildResult = null;
    private string $zipDirectory;
    
    /**
     * __construct Initialize the builder with all the files included in the array arg.
     *
     * @param  mixed $files A array of associative arrays with `name` & `binary` keys.
     * @return void
     */
    public function __construct(array $files){
        $zipName = uniqid().".zip";
        $directory = __DIR__ . "/../public/$zipName";

        $this->zipDirectory = $directory;
        $this->zipArchive = new ZipArchive();

        $result = $this->zipArchive->open($directory, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        if(!$result) throw new Error("Can't create the ZIP file.");

        foreach ($files as $file) {
            $this->add($file['name'], $file['binary']);
        }
    }
    
    /**
     *  Adds a file into the zip file opened.
     *
     * @param  string $name name of the file to add.
     * @param  string $fileContent the binary content of the file.
     * @return static
     */
    public function add(string $name, string $fileContent): static{
        $this->zipArchive->addFromString($name, $fileContent);
        return $this;
    }
    
    /**
     *  Closes current Zip opened and return its path.
     *
     * @return string
     */
    public function build(): string{
        $this->zipArchive->close();
        return $this->zipDirectory;
    }
}