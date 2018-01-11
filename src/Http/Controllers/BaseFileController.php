<?php

namespace Fomvasss\FieldFile\Http\Controllers;

use Fomvasss\FieldFile\Http\Controllers\Traits\RequestValidationTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * Class BaseFileController
 *
 * @package \Fomvasss\FieldFile\Http\Controllers
 */
abstract class BaseFileController extends Controller
{
    use RequestValidationTrait;

    protected $manager;

    protected $allowedFieldNames;

    public $currentFieldName = 'file';

    /**
     * @param \Illuminate\Http\Request $request
     * @return int
     */
    public function upload(Request $request)
    {
        foreach ($this->allowedFieldNames as $fieldName) {
            $this->validate($request, $fieldName);

            if ($request->hasFile($fieldName)) {
                $result = $this->manager->store($request->file($fieldName));
                $this->currentFieldName = $fieldName;

                return $result;
            }
        }

        return 0;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function uploadMultiple(Request $request)
    {
        $ids = [];

        foreach ($this->allowedFieldNames as $fieldName) {
            if ($request->hasFile($fieldName)) {
                $this->validateArray($request, $fieldName);

                $res = $this->manager->storeMultiple($request->file($fieldName));
                $ids = array_merge($ids, $res);
                $this->currentFieldName = $fieldName;
            }
        }

        return $ids;
    }

    public function show($id)
    {
        return $this->manager->findOrFail($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getFile($id)
    {
        $url = $this->manager->getPath($id);

        return response()->file($url);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function getUrl($id)
    {
        $url = $this->manager->getPath($id);

        return url($url);
    }

    /**
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        return $this->manager->safeDelete($id) ? 1 : 0;
    }
}
