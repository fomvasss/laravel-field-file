<?php

namespace Fomvasss\FieldFile\Http\Controllers;

use Fomvasss\FieldFile\Http\Controllers\Traits\ResponseTrait;
use Fomvasss\FieldFile\Managers\ImageFileManager;
use Illuminate\Http\Request;

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

        $this->validationRules = config('field-file.fields.image.rules', []);

        $this->allowedFieldNames = config('field-file.fields.image.allowed_names', ['image']);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function upload(Request $request)
    {
        $result = parent::upload($request);

        return $this->responseUpload(['id' => $result]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function uploadMultiple(Request $request)
    {
        $result = parent::uploadMultiple($request);

        return $this->responseUpload(['id' => $result]);
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
