<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HoraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        collect([
            '07:00:00',
            '08:00:00',
            '09:00:00',
            '10:00:00',
            '11:00:00',
            '12:00:00',
            '13:00:00',
            '14:00:00',
            '15:00:00',
            '16:00:00',
            '17:00:00'
        ])->map(function($item) {
            DB::table('horas')->insert([
                'hora' => $item,
                'created_at' => Carbon::now()->format('Y-m-d H:m:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:m:s')
            ]);
        });
    }
}
