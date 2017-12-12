<?php

namespace Fomvasss\FieldFile\Services\ImageMaker;

use Intervention\Image\Facades\Image;

/**
 * Class OriginalImageMaker
 *
 * @package \Fomvasss\FieldFile\Managers
 */
class OriginalImageMaker extends BaseImageMaker
{
    /**
     * For example, this maker set:
     * - max weight, height
     * - original aspect ratio
     * - disabled enlargement (upsize)
     * - format and quality
     * More options see this http://image.intervention.io/
     */
    public function make()
    {
        Image::make($this->image)
            ->resize($this->weight, $this->height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->save($this->path . '/' . $this->fileName.'.'.$this->format, $this->compress);
    }
}
