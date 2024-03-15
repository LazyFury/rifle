<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('RoleSeeder start');

        // $this->command->info(Role::where('name','admin')->first());

        if (Role::where('name', 'admin')->first()) {
            $this->command->info('Role admin role already exists.');
        } else {
            Role::create(['name' => 'admin', 'remark' => '管理员']);
        }

        // 商户
        if (Role::where('name', 'merchant')->first()) {
            $this->command->info('Role merchant role already exists.');
        } else {
            Role::create(['name' => 'merchant', 'remark' => '商户']);
        }

        // user
        if (Role::where('name', 'user')->first()) {
            $this->command->info('Role user role already exists.');
        } else {
            Role::create(['name' => 'user', 'remark' => '用户']);
        }


        // assign role admin to user id 3
        $user = User::find(3);
        logger("user", [$user]);
        if ($user) {
            $user->assignRole('admin');
        }

    }
}
