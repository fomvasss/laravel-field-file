<?php

namespace Fomvasss\FieldFile\Http\Controllers;

use Illuminate\Routing\Controller;

/**
 * Class BaseFileController
 *
 * @package \Fomvasss\FieldFile\Http\Controllers
 */
abstract class BaseFileController extends Controller
{
    public $manager;

    public $allowedFieldNames;

    /**
     * @param \Illuminate\Http\Request $request
     * @return int
     */
    public function uploadBase($request)
    {
        foreach ($this->allowedFieldNames as $fieldName) {
            if ($request->hasFile($fieldName)) {
                $result = $this->manager->store($request->file($fieldName));

                return $result;
            }
        }

        return 0;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function uploadMultipleBase($request)
    {
        $ids = [];

        foreach ($this->allowedFieldNames as $fieldName) {
            if ($request->hasFile($fieldName)) {
                $res = $this->manager->storeMultiple($request->file($fieldName));
                $ids = array_merge($ids, $res);
            }
        }

        return $ids;
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
     * @return bool
     */
    public function delete($id)
    {
        return $this->manager->safeDelete($id) ? 1 : 0;
    }
}
