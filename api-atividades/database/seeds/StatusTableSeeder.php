<?php

use Illuminate\Database\Seeder;

class StatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $status = ['Done', 'WIP', 'Review', 'To Do'];

        foreach ($status as $st) {
            \App\Models\Status::firstOrCreate([
                'name' => $st
            ]);
        }
    }
}
