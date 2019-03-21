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
        $start = strtotime($this->start);

        if ($this->include_lunch == 1) {
            $start += 60*30;
        }

        return date('H:i', strtotime($this->end) - $start);
    }
}
