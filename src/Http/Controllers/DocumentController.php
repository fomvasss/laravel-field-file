<?php

namespace Fomvasss\FieldFile\Http\Controllers;

use Fomvasss\FieldFile\Http\Controllers\Traits\ResponseTrait;
use Fomvasss\FieldFile\Managers\DocumentFileManager;
use Illuminate\Http\Request;

class DocumentController extends BaseFileController
{
    use ResponseTrait;

    /**
     * DocumentController constructor.
     *
     * @param \Fomvasss\FieldFile\Managers\DocumentFileManager $manager
     */
    public function __construct(DocumentFileManager $manager)
    {
        $this->manager = $manager;

        $this->validationRules = config('field-file.fields.document.rules', []);

        $this->allowedFieldNames = config('field-file.fields.document.allowed_names', ['file']);
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
