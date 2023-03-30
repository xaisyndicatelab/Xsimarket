<?php

namespace Xsimarket\Database\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Xsimarket\Traits\TranslationTrait;
use Illuminate\Database\Eloquent\Builder;


class DeliveryTime extends Model
{
    use TranslationTrait, Sluggable;

    protected $table = 'delivery_times';

    protected $appends = ['translated_languages'];

    public $guarded = [];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function scopeWithUniqueSlugConstraints(Builder $query, Model $model): Builder
    {
        return $query->where('language', $model->language);
    }
}
