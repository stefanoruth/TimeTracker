<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = factory(App\User::class)->create(['email' => 'stefano6262@gmail.com']);

        $dates = [
            [
                'include_lunch' => true,
                'start' => '08:00',
                'end' => '16:00',
                'date' => date('Y-m-d', strtotime('monday last week')),
            ],
            [
                'include_lunch' => true,
                'start' => '08:00',
                'end' => '16:00',
                'date' => date('Y-m-d', strtotime('tuesday last week')),
            ],
            [
                'include_lunch' => true,
                'start' => '08:00',
                'end' => '17:00',
                'date' => date('Y-m-d', strtotime('wednesday last week')),
            ],
            [
                'include_lunch' => true,
                'start' => '08:00',
                'end' => '16:00',
                'date' => date('Y-m-d', strtotime('thursday last week')),
            ],
            [
                'include_lunch' => false,
                'start' => '18:00',
                'end' => '20:00',
                'date' => date('Y-m-d', strtotime('thursday last week')),
            ],
            [
                'include_lunch' => true,
                'start' => '08:00',
                'end' => '16:00',
                'date' => date('Y-m-d', strtotime('friday last week')),
            ],
            [
                'include_lunch' => false,
                'start' => '10:00',
                'end' => '12:00',
                'date' => date('Y-m-d', strtotime('saturday last week')),
            ],
            [
                'include_lunch' => true,
                'start' => '08:00',
                'end' => '16:00',
                'date' => date('Y-m-d', strtotime('monday this week')),
            ],
            [
                'include_lunch' => true,
                'start' => '08:00',
                'end' => '16:00',
                'date' => date('Y-m-d', strtotime('thursday this week')),
            ],
            [
                'include_lunch' => true,
                'start' => '08:00',
                'end' => '16:00',
                'date' => date('Y-m-d', strtotime('thursday this week')),
            ],
            [
                'include_lunch' => true,
                'start' => '09:00',
                'end' => '12:00',
                'date' => date('Y-m-d', strtotime('thursday this week')),
            ],
            [
                'include_lunch' => false,
                'start' => '08:00',
                'end' => '16:00',
                'date' => date('Y-m-d', strtotime('friday this week')),
            ],
        ];

        // $week = date('W');
        // $day = date('N');

        $user->timeRegistrations()->createMany($dates);
    }
}
