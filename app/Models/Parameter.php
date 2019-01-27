<?php

namespace WSCAERD\Models;

use Illuminate\Database\Eloquent\Model;

class Parameter extends Model
{
    protected $table = "Parameters";
    protected $primaryKey = "parameterID";
    public $timestamps = false;
    protected $guarded = [];
}
