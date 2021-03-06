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

    'save_original_name' => false,

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
                    'item' => 'required|file|min:1|max:20000|mimes:pdf,doc,docx,txt,xls,xlsx,png,jpeg,jpg,gif'
                ],
                'multiple' => [
                    'array' => 'required|array|max:10',
                    'item' => 'required|file|min:1|max:20000|mimes:pdf,doc,docx,txt,xls,xlsx,png,jpeg,jpg,gif'
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
                    'item' => 'required|image|dimensions:min_width=50,min_height=50,max_width=5000,max_height=5000|max:20000', //|mimes:png,jpeg,jpg,gif
                ],
                'multiple' => [
                    'array' => 'required|array|max:10',
                    'item' => 'required|image|dimensions:min_width=50,min_height=50,max_width=5000,max_height=5000|max:20000',
                ],
            ],

            'path' => 'uploads/images', // root path for all  image-makers

            /*
             * Settings original image-maker
             */
            'compress' => 70,
            'weight' => 1920,
            'height' => 1080,
            'format' => 'jpg',
            'aspect_ratio' => true,
            'upsize' => true,

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
    'response' => [
        'json' => [
            'error_key_name'  => 'errors',
        ]
    ],

    /*
    * Old file
    *
    * Set the time (hours) for old unused file
    * This file can by delete
    *
    */
    'time_limit_old_file' => 24, // время, после которого доступно удалять не используемый файл с диска и базы, час.

];
