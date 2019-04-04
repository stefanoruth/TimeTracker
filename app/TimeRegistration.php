<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TimeRegistration extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            $start = strtotime($item->start);

            if ($item->include_lunch) {
                $start += 60 * 30;
            }

            $item->time = date('H:i', strtotime($item->end) - $start);
        });
    }
}
