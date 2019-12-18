<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::create([
            'name' => 'Nextem',
            'email' => 'test@nextem.com.br',
            'password' => '1234',
        ]);

        factory(\App\Models\User::class, 10)->create();
    }
}
