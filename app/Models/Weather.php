<?php

namespace WSCAERD\Models;

use Illuminate\Database\Eloquent\Model;

class Weather extends Model
{
    protected $guarded = [];
    protected $table = 'SkopjeWeatherAPIData';
    public $timestamps = false;
}
