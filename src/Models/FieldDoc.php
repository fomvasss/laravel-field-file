<?php

namespace Fomvasss\FieldFile\Models;

use Fomvasss\FieldFile\Models\Scopes\IsUsedFileScope;
use Fomvasss\FieldFile\Models\Traits\EventFieldFileModel;
use Illuminate\Database\Eloquent\Model;

class FieldDoc extends Model
{
    use EventFieldFileModel;

    public $timestamps = false;

    protected $with = ['file'];

    protected $fillable = ['file_id', 'title', 'weight'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new IsUsedFileScope);
    }

    /**
     * Field FieldDoc belongs (refers) to one model
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function fieldDocable()
    {
        return $this->morphTo();
    }

    /**
     * Field FieldDoc belongs (refers) to file
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function file()
    {
        return $this->belongsTo(File::class);
    }
}
