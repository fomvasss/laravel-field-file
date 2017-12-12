<?php

namespace Fomvasss\FieldFile\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'name',
        'path',
        'type',
        'mime_type',
        'size',
        'is_used',
        'user_id',
    ];

    /**
     * File has one FieldDoc
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function fieldDoc()
    {
        return $this->hasOne(FieldDoc::class);
    }

    /**
     * File has one FieldImage
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function fieldImage()
    {
        return $this->hasOne(FieldImage::class);
    }

    /**
     * File has one FieldAvatar
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function fieldAvatar()
    {
        return $this->hasOne(FieldAvatar::class);
    }
}
