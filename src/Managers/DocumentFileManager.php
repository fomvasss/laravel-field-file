<?php

namespace Fomvasss\FieldFile\Managers;

use Fomvasss\FieldFile\Models\File;

/**
 * Class DocumentManager
 *
 * @package Fomvasss\FieldFile\Managers
 */
class DocumentFileManager extends BaseFileManager
{
    /**
     * DocumentManager constructor.
     *
     * @param \Fomvasss\FieldFile\Models\File $fileModel
     */
    public function __construct(File $fileModel)
    {
        parent::__construct($fileModel);

        $this->destinationPath = config(
            'field_file.fields.document.path',
            'uploads/documents'
        );
    }

    /**
     * @param $requestFile
     * @param array $attr [path, type]
     * @param bool $returnModel
     * @return \Fomvasss\FieldFile\Models\File|mixed
     */
    public function store($requestFile, array $attr = [], $returnModel = false)
    {
        $this->destinationPath = $attr['path'] ?? $this->destinationPath;
        $this->checkMakeDir($this->destinationPath);

        $fileAttributes = $this->getFileAttributes($requestFile, [
            'type' => $attr['type'] ??  'document',
            'path' => $this->destinationPath
        ]);

        $fileNameOnDisk = $this->putFile($requestFile, $this->generateName($fileAttributes));

        $model = $this->saveDb($fileNameOnDisk, $fileAttributes);

        return $returnModel ? $model : $model->id;
    }

    /**
     * @param $requestFile
     * @param string $fileName
     * @return mixed
     */
    public function putFile($requestFile, string $fileName)
    {
        $fileExtension = $requestFile->getClientOriginalExtension();
        $fileName = $fileName.'.'.$fileExtension;

        $requestFile->move($this->destinationPath, $fileName);

        return $fileName;
    }
}
