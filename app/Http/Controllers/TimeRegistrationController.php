<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

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

        return $dateMap->mapWithKeys(function ($dateLang, $date) use ($registations) {
            $entries = $registations->get($date) ?? new Collection();

            return [$date => [
                'date' => $dateLang,
                'totalTime' => date('H:i', $entries->where('vacation', 0)->sum(function ($entry) {
                    return strtotime($entry->time);
                })),
                'registrations' => $entries,
            ]];
        });
    }

    public function buildWeek(Carbon $date)
    {
        return Collection::make([
            $date->startOfWeek()->addDay(0)->format('Y-m-d') => 'Mandag',
            $date->startOfWeek()->addDay(1)->format('Y-m-d') => 'Tirsdag',
            $date->startOfWeek()->addDay(2)->format('Y-m-d') => 'Onsdag',
            $date->startOfWeek()->addDay(3)->format('Y-m-d') => 'Torsdag',
            $date->startOfWeek()->addDay(4)->format('Y-m-d') => 'Fredag',
            $date->startOfWeek()->addDay(5)->format('Y-m-d') => 'Lørdag',
            $date->startOfWeek()->addDay(6)->format('Y-m-d') => 'Søndag',
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
