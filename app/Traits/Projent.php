<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait Projent
{

    public function place()
    {
        return $this->belongsTo('App\Models\Place');
    }

    public function author()
    {
        return $this->belongsTo('App\Models\User', 'author_id');
    }

    public static function create(array $attributes = [])
    {

        $model = static::query()->create($attributes + ['author_id' => Auth::id()]);

        $model->storeCaldates($attributes['caldates']);

        $model->storeImage($attributes['image']);

        $model->storeTags($attributes['tags']);

        return $model;

    }

    public function update(array $attributes = [], array $options = [])
    {

        $response = parent::update($attributes, $options);

        $this->updateCaldates($attributes['caldates']);

        $this->updateImage($attributes['image']);

        $this->updateTags($attributes['tags']);

        return $response;

    }

    public function delete()
    {

        $this->deleteCaldates();

        $this->deleteImage();

        $this->deleteTags();

        $response = parent::delete();

        return $response;
    }

}
