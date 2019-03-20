<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TimeRegiration extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    protected $casts = []
}
