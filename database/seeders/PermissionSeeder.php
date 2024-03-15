<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Permission
        $command = $this->command;
        function createPermissionForModel(string $model, $actions = ['create', 'detail', 'update', 'delete', 'list', "*"], $command = null)
        {
            foreach ($actions as $action) {
                $key = "model." . $model . '.' . $action;
                if (
                    Permission::where([
                        'name' => $key
                    ])->first()
                ) {
                    if ($command != null) {
                        $command->info('Permission ' . $key . ' already exists.');
                    }
                    continue;
                }

                Permission::create(['name' => $key]);

                if ($command != null) {
                    $command->info('!!!【created】Permission ' . $key . ' created.');
                }
            }
        }

        createPermissionForModel('posts', command: $command);
        createPermissionForModel('users', command: $command);
        createPermissionForModel('roles', command: $command);
        createPermissionForModel('permissions', command: $command);
    }
}
