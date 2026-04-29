<?php

namespace Kejubayer\BdAddress\Traits;

use Illuminate\Support\Str;

trait SlugTrait
{
    protected static function bootSlugTrait()
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name_en);
            }
        });
    }
}
