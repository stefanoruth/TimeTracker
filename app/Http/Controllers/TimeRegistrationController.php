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
                'totalTime' => date('H:i', $entries->where('vacation', 0)->sum(function ($entry) {
                    return strtotime($entry->time);
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
        $query = $user->timeRegistrations()->select(DB::raw("SUM(DATE_FORMAT(time, '%k')*60+DATE_FORMAT(time, '%i')) as flex"));
        $workedTime = (clone $query)->where('vacation', 0)->value('flex');
        $vecationTime = (clone $query)->where('vacation', 1)->value('flex');
        dump($workedTime, $vecationTime, $user->settings);

        $weeksSignedUp = $user->created_at->diffInWeeks(Carbon::now());
        $shouldWorkedMins = $user->settings->work * $weeksSignedUp + 0;
        $workedMins = $workedTime + $vecationTime;


        dump($shouldWorkedMins, $workedMins);

        $flex = $workedMins - $shouldWorkedMins;

        $hours = $flex / 60;
        $minutes = $flex % 60;

        return sprintf('%02d:%02d', $hours, $minutes);
    }

    public function weekHours(Collection $days)
    {
        $totalWeek = $days->sum(function ($day) {
            [$hours, $min] = explode(':', $day['totalTime']);

            return $hours * 60 + $min;
        });

        $hours = $totalWeek / 60;
        $minutes = $totalWeek % 60;

        return sprintf('%02d:%02d', $hours, $minutes);
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
            'lunch' => 'required|boolean',
        ]);

        return Auth::user()->timeRegistrations()->create(
            $data
        );
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'id' =>'required|integer',
            'start' => 'required|date_format:H:i',
            'end' => 'required|date_format:H:i',
            'date' => 'required|date_format:Y-m-d',
            'note' => 'nullable',
            'vacation' => 'required|boolean',
            'lunch' => 'required|boolean',
        ]);

        return Auth::user()->timeRegistrations()->where('id', $data['id'])->update($data);
    }

    public function destroy(Request $request)
    {
        $data = $request->validate(['id' => 'required|integer']);

        return Auth::user()->timeRegistrations()->delete($data['id']);
    }
}
