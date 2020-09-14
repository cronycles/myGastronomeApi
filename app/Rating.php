<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'location',
        'food',
        'service',
        'value_of_money',
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function place() {
        return $this->belongsTo('App\Place');
    }
}
