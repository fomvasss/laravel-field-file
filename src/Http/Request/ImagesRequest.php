<?php

namespace Fomvasss\FieldFile\Http\Request;

class ImagesRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return config('field_file.fields.image.rules.multiple', [
            'image' => 'required|array|max:10',
            'image.*' => 'required|image|dimensions:min_width=50,min_height=50,max_width=5000,max_height=5000|max:20000', //|mimes:png,jpeg,jpg,gif
        ]);
    }
}
