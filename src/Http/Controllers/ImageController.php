<?php

namespace Fomvasss\FieldFile\Http\Controllers;

use Fomvasss\FieldFile\Http\Request\ImageRequest;
use Fomvasss\FieldFile\Http\Request\ImagesRequest;
use Fomvasss\FieldFile\Managers\ImageFileManager;

class ImageController extends BaseFileController
{
    use ResponseTrait;

    /**
     * ImageController constructor.
     *
     * @param \Fomvasss\FieldFile\Managers\ImageFileManager $manager
     */
    public function __construct(ImageFileManager $manager)
    {
        $this->manager = $manager;

        $this->allowedFieldNames = config(
            'field_file.fields.image.allowed_names',
            ['image', 'img', 'file']
        );
    }

    /**
     * @param \Fomvasss\FieldFile\Http\Request\ImageRequest $request
     * @return int
     */
    public function upload(ImageRequest $request)
    {
        $result = parent::uploadBase($request);

        return $this->responseUpload(['image' => $result]);
    }

    /**
     * @param \Fomvasss\FieldFile\Http\Request\ImagesRequest $request
     * @return array
     */
    public function uploadMultiple(ImagesRequest $request)
    {
        $result = parent::uploadMultipleBase($request);

        return $this->responseUpload(['images' => $result]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        return $this->responseDelete(parent::delete($id));
    }
}
