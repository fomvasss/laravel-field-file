<?php

namespace Fomvasss\FieldFile\Services\ImageMaker;

abstract class BaseImageMaker
{
    protected $image;

    protected $path;

    protected $filename;

    protected $format;

    protected $compress;

    protected $height;

    protected $weight;

    protected $user;

    /**
     * BaseImageMaker constructor.
     *
     * @param $image
     * @param $path
     * @param $fileName
     */
    public function __construct($image, $path, $fileName)
    {
        $this->image = $image;
        $this->path = $path;
        $this->fileName = $fileName;
        $this->format = config('field_file.fields.image.format', 'jpg');
        $this->compress = config('field_file.fields.image.compress', 70);
        $this->weight = config('field_file.fields.image.weight', 1920);
        $this->height = config('field_file.fields.image.height', 1080);

        $this->user = \Auth::user();

        $this->make();
    }

    abstract public function make();

    /**
     * @param string $path
     * @return string
     */
    protected function checkMakeDir(string $path)
    {
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
        return $path;
    }
}
