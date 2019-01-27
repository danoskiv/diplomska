<?php

namespace WSCAERD\Models;

use Illuminate\Database\Eloquent\Model;

class SensorData extends Model
{
    protected $table = 'SensorData';
    protected $primaryKey = 'recId';
    public $timestamps = false;
}
