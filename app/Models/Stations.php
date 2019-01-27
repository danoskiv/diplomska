<?php

namespace WSCAERD\Models;

use Illuminate\Database\Eloquent\Model;

class Stations extends Model
{
	protected $guarded = [];
    protected $table = 'Stations';
    protected $primaryKey = 'recId';
    public $timestamps = false;
}
