<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Field extends Model
{
    const CACHE_TIMEOUT_MINUTES = 60;

    protected $guarded = [];

    public function people() {
        return $this->belongsToMany(Person::class, 'people_fields');
    }

    public static function allCached() {
        return Cache::remember('fields', self::CACHE_TIMEOUT_MINUTES, function() {
            return self::orderBy('field','asc')->get()->toArray();
        });
    }
}
