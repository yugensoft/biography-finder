<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Person extends Model
{
    const COUNTRY_CACHE_TIMEOUT_MINUTES = 60;

    protected $casts = [
        'achievements'=>'array',
        'books'=>'array',
    ];
    protected $appends = array('books_textarea');
    protected $guarded = [];

    public static function allCountriesCached()
    {
        return Cache::remember('countries', self::COUNTRY_CACHE_TIMEOUT_MINUTES, function() {
            $countries = self::query()->select('birth_country')->distinct()->pluck('birth_country')->toArray();

            $countriesFull = [];
            $allCountries = Countries::ALL;
            foreach($countries as $countryCode){
                $index = strtoupper($countryCode);
                if(isset($allCountries[$index])){
                    $countriesFull[$index] = $allCountries[$index];
                }
            }

            return $countriesFull;
        });
    }

    public function fields() {
        return $this->belongsToMany(Field::class, 'people_fields');
    }

    /**
     * Render the books as lines of [url], [title]
     */
    public function getBooksTextareaAttribute() {
        return array_reduce(
            $this->books,
            function($a,$b){
                return ($a == null ? '' : "$a\n") . $b['url'] ?? '' . ", " . $b['title'] ?? '';
            }
        );
    }
}
