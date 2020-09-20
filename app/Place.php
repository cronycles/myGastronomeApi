<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'latitude',
        'longitude',
    ];


    public function ratings() {
        return $this->hasMany('App\Rating');
    }

    public function users() {
        return $this->belongsToMany('App\User')
            ->withPivot('is_favourite', 'is_want_to_go', 'rating_id', 'notes')
            ->withTimestamps();
    }

}
