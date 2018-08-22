<?php

class UsersTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('users')->truncate();


        $dateTime = new DateTime('now');
        $dateTime = $dateTime->format('Y-m-d H:i:s');

        $users = [
            [
                'firstname'  => 'Alfred',
                'lastname'  => 'Nutile',
                'admin'     => 1,
                'active'     => 1,
                'email'      => 'admin@example.com',
                'password'   => Hash::make('admin'),
                'created_at' => $dateTime,
                'updated_at' => $dateTime,
            ]
        ];
        DB::table('users')->insert($users);

        $users = [
            [
                'firstname'  => 'Test',
                'lastname'  => 'Two',
                'admin'     => 1,
                'active'     => 1,
                'email'      => 'test@gmail.com',
                'password'   => Hash::make('admin'),
                'created_at' => $dateTime,
                'updated_at' => $dateTime,
            ],
            [
                'firstname'  => 'Test',
                'lastname'  => 'Three',
                'admin'     => 0,
                'active'     => 1,
                'email'      => 'test3@gmail.com',
                'password'   => Hash::make('password'),
                'created_at' => $dateTime,
                'updated_at' => $dateTime,
            ]
        ];
        DB::table('users')->insert($users);
    }
}
