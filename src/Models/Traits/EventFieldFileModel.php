<?php

namespace Fomvasss\FieldFile\Models\Traits;

use Illuminate\Database\Eloquent\Model;

trait EventFieldFileModel
{
    protected static function bootEventFieldFileModel()
    {
        static::created(function (Model $model) {
            $model->file->update(['is_used' => 1]);
        });

        static::updated(function (Model $model) {
            $model->file->update(['is_used' => 1]);
        });

        static::deleted(function (Model $model) {
            $model->file->update(['is_used' => 0]);
        });
    }
}
