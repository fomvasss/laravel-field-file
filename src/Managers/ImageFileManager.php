<?php

namespace Fomvasss\FieldFile\Managers;

use Fomvasss\FieldFile\Models\File;

/**
 * Class DocumentManager
 *
 * @package Fomvasss\FieldFile\Managers
 */
class ImageFileManager extends BaseFileManager
{
    protected $imageMakers = [];

    /**
     * ImageManager constructor.
     *
     * @param \Fomvasss\FieldFile\Models\File $fileModel
     */
    public function __construct(File $fileModel)
    {
        parent::__construct($fileModel);

        $this->destinationPath = config(
            'field-file.fields.image.path',
            'uploads/images'
        );
    }

    /**
     * @param $requestFile
     * @param array $attr [path, image_makers[], type, custom_file_name]
     * @param bool $returnModel
     * @return \Fomvasss\FieldFile\Models\File|mixed
     */
    public function store($requestFile, array $attr = [], $returnModel = false)
    {
        $this->destinationPath = $attr['path'] ?? $this->destinationPath;
        $this->checkMakeDir($this->destinationPath);

        $this->imageMakers = $attr['image_makers'] ?? [];

        $fileAttributes = $this->getFileAttributes($requestFile, [
            'type' => $attr['type'] ?? 'image',
            'custom_file_name' => $attr['custom_file_name'] ?? '',
            'path' => $this->destinationPath
        ]);

        $fileNameOnDisk = $this->putFile($requestFile, $this->generateName($fileAttributes));

        $model = $this->saveDb($fileNameOnDisk, $fileAttributes);

        return $returnModel ? $model : $model->id;
    }

    /**
     * @param $requestFile
     * @param string $fileName
     * @return string
     */
    public function putFile($requestFile, string $fileName)
    {
        $imageMakers = array_unique(array_merge(
            config('field-file.fields.image.makers', []),
            $this->imageMakers,
            [\Fomvasss\FieldFile\Services\ImageMaker\OriginalImageMaker::class]
        ));

        foreach ($imageMakers as $maker) {
            if (class_exists($maker)) {
                new $maker($requestFile, $this->destinationPath, $fileName);
            } else {
                \Log::error("Class '{$maker}' not found. Image file not created");
            }
        }

        $format = config('field-file.fields.image.format', 'jpg');
        $fileName = $fileName .'.'. $format;

        return $fileName;
    }
}
