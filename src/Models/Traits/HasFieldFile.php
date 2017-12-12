<?php

namespace Fomvasss\FieldFile\Models\Traits;

use Fomvasss\FieldFile\Models\FieldAvatar;
use Fomvasss\FieldFile\Models\FieldDoc;
use Fomvasss\FieldFile\Models\FieldImage;

trait HasFieldFile
{
    /**
     * Сущность Post имеет много полей файлов-документов (полиморфных)
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function fieldDocs()
    {
        return $this->morphMany(FieldDoc::class, 'field_docable');
    }

    /**
     * Сущность Post имеет много полей файлов-изображений (полиморфных)
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function fieldImages()
    {
        return $this->morphMany(FieldImage::class, 'field_imageable');
    }

    /**
     * Сущность Post имеет одно поле файл-логотип (полиморфных)
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function fieldAvatar()
    {
        return $this->morphOne(FieldAvatar::class, 'field_avatarable');
    }
}
