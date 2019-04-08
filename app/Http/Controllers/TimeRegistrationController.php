<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use App\TimeRegistration;
use Illuminate\Support\Facades\DB;

class TimeRegistrationController extends Controller
{
    public function index(Request $request)
    {
        $date =Carbon::now();
        $date->setISODate(
            $request->get('year', date('Y')),
            $request->get('week', date('W'))
        );

        $dateMap = $this->buildWeek($date);

        $registations = Auth::user()
            ->timeRegistrations()
            ->whereBetween('date', [
                $date->startOfWeek()->format('Y-m-d'),
                $date->endOfWeek()->format('Y-m-d'),
            ])
            ->get()
            ->groupBy('date');

        $days = $dateMap->map(function ($item) use ($registations) {
            $entries = $registations->get($item['date']) ?? new Collection();


            return array_merge($item, [
                'totalTime' => $this->formatTime($entries->where('vacation', 0)->sum(function ($entry) {
                    [$hours, $min] = explode(':', $entry->time);

                    return $hours * 60 + $min;
                })),
                'registrations' => $entries,
            ]);
        });

        return [
            'week' => $date->format('W'),
            'year' => $date->format('Y'),
            'weekHours' => $this->weekHours($days),
            'flex' => $this->flex(),
            'time' => $days,
        ];
    }

    public function flex()
    {
        $user = Auth::user();
        $timeRegistered = $user->timeRegistrations()->select(DB::raw("SUM(DATE_FORMAT(time, '%k')*60+DATE_FORMAT(time, '%i')) as flex"))->whereDate('date', '!=', date('Y-m-d'))->value('flex');
        $week = [1=>'monday', 2=>'tuesday', 3=>'wednesday', 4=>'thursday', 5=>'friday', 6=>'saturday', 7=>'sunday'];

        $workTimeThisWeek = Collection::make($user->settings->days)->map(function ($active, $day) {
            return compact('day', 'active');
        })->map(function ($item) use ($user, $week) {
            $i = array_search($item['day'], $week);

            if (!$item['active']) {
                return 0;
            }
            if (intval(date('N')) <= $i) {
                return 0;
            }

            return $user->workPrDay();
        })->sum();

        $weeksSignedUp = $user->created_at->diffInWeeks(Carbon::now());
        $shouldWorkedMins = $user->settings->work * $weeksSignedUp + $workTimeThisWeek;

        $flex = $timeRegistered - $shouldWorkedMins;

        return $this->formatTime($flex);
    }

    public function weekHours(Collection $days)
    {
        $totalWeek = $days->sum(function ($day) {
            [$hours, $min] = explode(':', $day['totalTime']);

            return $hours * 60 + $min;
        });

        return $this->formatTime($totalWeek);
    }

    public function formatTime($min)
    {
        $hours = $min / 60;
        $minutes = $min % 60;

        return sprintf('%02d:%02d', $hours, abs($minutes));
    }

    public function buildWeek(Carbon $date)
    {
        return Collection::make([
            ['date' => $date->startOfWeek()->addDay(0)->format('Y-m-d'), 'weekDay' => 'Mandag'],
            ['date' => $date->startOfWeek()->addDay(1)->format('Y-m-d'), 'weekDay' => 'Tirsdag'],
            ['date' => $date->startOfWeek()->addDay(2)->format('Y-m-d'), 'weekDay' => 'Onsdag'],
            ['date' => $date->startOfWeek()->addDay(3)->format('Y-m-d'), 'weekDay' => 'Torsdag'],
            ['date' => $date->startOfWeek()->addDay(4)->format('Y-m-d'), 'weekDay' => 'Fredag'],
            ['date' => $date->startOfWeek()->addDay(5)->format('Y-m-d'), 'weekDay' => 'Lørdag'],
            ['date' => $date->startOfWeek()->addDay(6)->format('Y-m-d'), 'weekDay' => 'Søndag'],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'start' => 'required|date_format:H:i',
            'end' => 'required|date_format:H:i',
            'date' => 'required|date_format:Y-m-d',
            'note' => 'nullable',
            'vacation' => 'required|boolean',
            'include_lunch' => 'required|boolean',
        ]);

        return Auth::user()->timeRegistrations()->create(
            $data
        );
    }

    public function destroy($id)
    {
        return Auth::user()->timeRegistrations()->where('id', $id)->delete();
    }
}
