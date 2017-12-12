<?php

namespace Fomvasss\FieldFile\Http\Request;

class DocumentRequest extends BaseRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return config('field_file.fields.document.rules.single', [
                'document' => 'required|file|min:1|max:20000|mimes:pdf,doc,docx,txt,xls,xlsx,png,jpeg,jpg,gif'
        ]);
    }
}
