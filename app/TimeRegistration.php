<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TimeRegistration extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    protected $appends = ['time'];

    public function getTimeAttribute()
    {
        return date('H:i', strtotime($this->end) - strtotime($this->start));
    }
}
