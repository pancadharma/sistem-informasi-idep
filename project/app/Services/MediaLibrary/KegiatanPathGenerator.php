<?php

namespace App\Services\MediaLibrary;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator as BasePathGenerator;

class KegiatanPathGenerator implements BasePathGenerator
{
    public function getPath(Media $media): string
    {
        return $media->model->id . '/' . $media->collection_name . '/';
    }

    public function getPathForConversions(Media $media): string
    {
        return $media->model->id . '/' . $media->collection_name . '/conversions/';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return $media->model->id . '/' . $media->collection_name . '/responsive-images/';
    }

    protected function getBasePath(Media $media): string
    {
        $prefix = config('media-library.prefix', '');

        if ($prefix !== '') {
            return $prefix . '/' . $media->model->id . '/' . $media->collection_name;
        }
        return $media->model->id . '/' . $media->collection_name;
    }
}