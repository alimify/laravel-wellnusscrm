<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('statuses')->insert([
            [
                'id' => 1,
                'title' => 'Confirmed',
                'class' => 'btn btn-success'

            ],
            [
                'id' => 2,
                'title' => 'Cancelled',
                'class' => 'btn btn-danger'
            ],
            [
                'id' => 3,
                'title' => 'Hold',
                'class' => 'btn btn-warning'
            ]
        ]);
    }
}
