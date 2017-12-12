<?php

/*
|--------------------------------------------------------------------------
| Field file manager
|--------------------------------------------------------------------------
|
*/
return [
    /*
     * Package routes
     *
     * The routes for upload,
     * getFile and delete files
     *
     */
    'routes' => [
        'use' => true,
        'namespace' => 'Fomvasss\FieldFile\Http\Controllers',
        'middlewares' => ['web'],
        'prefix' => 'file',
        'as' => 'file.'
    ],

    /*
     * File fields types
     */
    'fields' => [
        'document' => [
            'allowed_names' => ['document', 'file'],
            /*
             * Laravel rules request validation Documents.
             */
            'rules' => [
                'single' => [
                    'document' => 'required|file|min:1|max:20000|mimes:pdf,doc,docx,txt,xls,xlsx,png,jpeg,jpg,gif'
                ],
                'multiple' => [
                    'document' => 'required|array|max:10',
                    'document.*' => 'required|file|min:1|max:20000|mimes:pdf,doc,docx,txt,xls,xlsx,png,jpeg,jpg,gif'
                ],
            ],
            'path' => 'uploads/documents',
        ],

        'image' => [
            'allowed_names' => ['image', 'avatar'],
            /*
             * Laravel rules request validation Images.
             */
            'rules' => [
                'single' => [
                    'image' => 'required|image|dimensions:min_width=50,min_height=50,max_width=5000,max_height=5000|max:20000', //|mimes:png,jpeg,jpg,gif
                ],
                'multiple' => [
                    'image' => 'required|array|max:10',
                    'image.*' => 'required|image|dimensions:min_width=50,min_height=50,max_width=5000,max_height=5000|max:20000',
                ],
            ],
            'path' => 'uploads/images',
            'compress' => 70,
            'format' => 'jpg',

            /*
             * Custom Intervention-image maker class
             *
             * See for example default original and example maker:
             * '\Fomvasss\FieldFile\Services\ImageMaker\OriginalImageMaker'
             * '\Fomvasss\FieldFile\Services\ImageMaker\ExampleImageMaker'
             *
             * You can create and add your maker class, for example:
             * '\App\ImageMakers\PageImageMaker'
             *
             * Also you can set the your maker class when
             * calling method 'store()' with class ImageManager
             */
            'makers' => [
                //'\App\MyImageMaker'
            ],
        ],
    ],
    'json_response_request' => [
        'key_name' => 'errors',
    ],

    /*
    * Old file
    *
    * Set the time (hours) for old unused file
    * This file can by delete
    *
    */
    'time_limit_old_file' => 24, // время, после которого можно удалять не используемый файл с диска и бызы, часов.

];
