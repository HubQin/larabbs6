<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 获取 Faker 实例
        $faker = app(Faker\Generator::class);

        // 头像假数据
        $avatars = [
            '1_1573961301_WioWOheY9j.png',
            '1_1573961360_13SsPykBcA.png',
            '1_1573961394_njX2PIwj8P.png',
            '1_1573961421_oJGQ0KQunT.png',
            '1_1573961439_bywK8Z2HZQ.png',
        ];

        $users = factory(User::class)->times(10)->make()->each(function ($user, $index) use ($faker, $avatars){
            $user->avatar = $faker->randomElement($avatars);
        });

        $user_array = $users->makeVisible(['password', 'remember_token'])->toArray();
        User::insert($user_array);

        $user = User::find(1);
        $user->name = 'Summer';
        $user->email = 'summer@example.com';
        $user->avatar = '1_1573961439_bywK8Z2HZQ.png';
        $user->save();

        // 初始化用户角色，将 1 号用户指派为『站长』
        $user->assignRole('Founder');

        // 将 2 号用户指派为『管理员』
        $user = User::find(2);
        $user->assignRole('Maintainer');
    }
}
