<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

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
                $start += 60 * User::where('id', $item->user_id)->value('settings')->lunch;
            }

            $item->time = date('H:i', strtotime($item->end) - $start);
        });
    }

    public function getStartAttribute($value)
    {
        return date('H:i', strtotime($value));
    }

    public function getEndAttribute($value)
    {
        return date('H:i', strtotime($value));
    }

    public function getTimeAttribute($value)
    {
        return date('H:i', strtotime($value));
    }
}
