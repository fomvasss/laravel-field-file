<?php

namespace Fomvasss\FieldFile\Models;

use Fomvasss\FieldFile\Models\Scopes\IsUsedFileScope;
use Fomvasss\FieldFile\Models\Traits\EventFieldFileModel;
use Illuminate\Database\Eloquent\Model;

class FieldImage extends Model
{
    use EventFieldFileModel;

    public $timestamps = false;

    protected $with = ['file'];

    protected $fillable = ['file_id', 'alt', 'title', 'weight'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new IsUsedFileScope);
    }

    /**
     * Field FieldImage belongs (refers) to one model
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function fieldImageable()
    {
        return $this->morphTo();
    }

    /**
     * Field FieldImage belongs (refers) to file
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function file()
    {
        return $this->belongsTo(File::class);
    }
}
