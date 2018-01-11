<?php

namespace Fomvasss\FieldFile\Http\Controllers\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ResponseTrait
{
    /**
     * @param $result
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseUpload($result)
    {
        $message = $result ? trans('field-file-translations::messages.file.upload.success') : trans('field-file-translations::messages.file.upload.error');

        return new JsonResponse([
            'data' => [
                $result
            ],
            'message' => $message
        ], Response::HTTP_CREATED);
    }

    /**
     * @param $result
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseDelete($result)
    {
        $message = $result ? trans('field-file-translations::messages.file.delete.success') : trans('field-file-translations::messages.file.delete.error');

        return new JsonResponse([
            'message' => $message
        ], Response::HTTP_ACCEPTED);
    }
}
