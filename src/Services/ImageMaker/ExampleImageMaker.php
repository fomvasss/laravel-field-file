<?php

namespace Fomvasss\FieldFile\Services\ImageMaker;

use Intervention\Image\Facades\Image;

/**
 * Class OriginalImageMaker
 *
 * @package \Fomvasss\FieldFile\Managers
 */
class ExampleImageMaker extends BaseImageMaker
{
    /**
     * For example, this maker set:
     * - max weight, height
     * - original aspect ratio
     * - disabled enlargement
     * - format and quality
     * More options see this http://image.intervention.io/
     *
     * - check user directory (and create if not exists)
     * - current user save directory (also allowed $this->user)
     *
     * $this->image - request file
     * $this->path - default root path
     * $this->fileName - generated file name
     * $this->format - needed format img, see http://image.intervention.io/api/encode
     * $this->compress - default compress
     */
    public function make()
    {
        $userId = \Auth::id() ?? 1;

        $userPath = $this->checkMakeDir($this->path . '/'.$userId);

        Image::make($this->image)
            ->resize(360, 280, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->save($userPath.'/' . $this->fileName.'.'.$this->format, $this->compress);
    }
}
