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
            // remove "*" from $actions
            $actions = array_filter($actions, function ($action) {
                return $action != "*";
            });

            $parent_key = "model." . $model . '.*';

            if (
                $parent =
                Permission::where([
                    'name' => $parent_key
                ])->first()
            ) {
                if ($command != null) {
                    $command->info('Permission ' . $parent_key . ' already exists.');
                }
            } else {
                $parent = Permission::create(['name' => $parent_key]);
                if ($command != null) {
                    $command->info('!!!【created】Permission ' . $parent_key . ' created.');
                }
            }

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

                Permission::create(['name' => $key, 'parent_id' => $parent->id]);

                if ($command != null) {
                    $command->info('!!!【created】Permission ' . $key . ' created.');
                }
            }
        }

        createPermissionForModel('posts', command: $command);
        createPermissionForModel('users', command: $command);
        createPermissionForModel('roles', command: $command);
        createPermissionForModel('permissions', command: $command);
        createPermissionForModel('api_manages', command: $command);
        createPermissionForModel('menus', command: $command);
        createPermissionForModel('dicts', command: $command);
        createPermissionForModel('dict_groups', command: $command);
        createPermissionForModel('post_categories', command: $command);
        createPermissionForModel('post_tags', command: $command);
        createPermissionForModel('post_comments', command: $command);
    }
}
