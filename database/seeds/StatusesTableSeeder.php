<?php

use Illuminate\Database\Seeder;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('statuses')->truncate();

        DB::table('statuses')->insert([
            'name'=>'Not Completed',
            'type' => 'order',
            'color' => '#a53838',
        ]);
        DB::table('statuses')->insert([
            'name'=>'Completed',
            'type' => 'order',
            'color' => '#449d44',
        ]);

        DB::table('statuses')->insert([
            'name'=>'In-Processing',
            'type' => 'design',
            'color' => '#a53838',
        ]);
        DB::table('statuses')->insert([
            'name'=>'Rejected',
            'type' => 'design',
            'color' => '#a53838',
        ]);
        DB::table('statuses')->insert([
            'name'=>'Reviewed',
            'type' => 'design',
            'color' => '#a53838',
        ]);
        DB::table('statuses')->insert([
            'name'=>'Approved',
            'type' => 'design',
            'color' => '#a53838',
        ]);
        DB::table('statuses')->insert([
            'name'=>'Update',
            'type' => 'design',
            'color' => '#a53838',
        ]);
        DB::table('statuses')->insert([
            'name'=>'No Design',
            'type' => 'design',
            'color' => '#a53838',
        ]);
    }
}
