<?php

namespace App\Services\MediaLibrary;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator as BasePathGenerator;

class ProgramPathGenerator implements BasePathGenerator
{
    public function getPath(Media $media): string
    {
        return $media->collection_name . '/';
    }

    public function getPathForConversions(Media $media): string
    {
        return $media->collection_name . '/conversions/';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return $media->collection_name . '/responsive-images/';
    }
    protected function getBasePath(Media $media): string
    {
        $prefix = config('media-library.prefix', '');

        if ($prefix !== '') {
            return $prefix . '/' . $media->collection_name;
        }
        return $media->collection_name;
    }
}
