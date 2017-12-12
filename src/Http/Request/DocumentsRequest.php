<?php

namespace Fomvasss\FieldFile\Http\Request;

class DocumentsRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return config('field_file.fields.document.rules.multiple', [
            'document' => 'required|array|max:10',
            'document.*' => 'required|file|min:1|max:20000|mimes:pdf,doc,docx,txt,xls,xlsx,png,jpeg,jpg,gif'
        ]);
    }
}
