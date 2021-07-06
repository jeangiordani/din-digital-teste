<?php

namespace Database\Seeders;

use App\Models\User;
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
        User::create([
            'name'      => 'Usuário Teste1',
            'email'     => 'teste@teste.com',
            'password'  => bcrypt('password'),
        ]);

        User::create([
            'name'      => 'Usuário Teste1',
            'email'     => 'teste2@teste.com',
            'password'  => bcrypt('password'),
        ]);
    }
}
