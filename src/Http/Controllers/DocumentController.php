<?php

namespace Fomvasss\FieldFile\Http\Controllers;

use Fomvasss\FieldFile\Http\Request\DocumentRequest;
use Fomvasss\FieldFile\Http\Request\DocumentsRequest;
use Fomvasss\FieldFile\Managers\DocumentFileManager;

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

        $this->allowedFieldNames = config(
            'field_file.fields.documents.allowed_names',
            ['document', 'doc', 'file']
        );
    }

    /**
     * @param \Fomvasss\FieldFile\Http\Request\DocumentRequest $request
     * @return \Fomvasss\FieldFile\Models\File|int|mixed
     */
    public function upload(DocumentRequest $request)
    {
        $result = parent::uploadBase($request);

        return $this->responseUpload(['document' => $result]);
    }

    /**
     * @param \Fomvasss\FieldFile\Http\Request\DocumentsRequest $request
     * @return array
     */
    public function uploadMultiple(DocumentsRequest $request)
    {
        $result = parent::uploadMultipleBase($request);

        return $this->responseUpload(['documents' => $result]);
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
