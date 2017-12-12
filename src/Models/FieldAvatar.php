<?php

namespace Fomvasss\FieldFile\Models;

use Fomvasss\FieldFile\Models\Scopes\IsUsedFileScope;
use Fomvasss\FieldFile\Models\Traits\EventFieldFileModel;
use Illuminate\Database\Eloquent\Model;

class FieldAvatar extends Model
{
    use EventFieldFileModel;

    public $timestamps = false;

    protected $with = ['file'];

    protected $fillable = ['file_id', 'alt', 'title'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new IsUsedFileScope);
    }

    /**
     *
     * Field FieldAvatar belongs (refers) to one model
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function fieldAvatarable()
    {
        return $this->morphTo();
    }

    /**
     * Field FieldAvatar belongs (refers) to file
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function file()
    {
        return $this->belongsTo(File::class);
    }
}
