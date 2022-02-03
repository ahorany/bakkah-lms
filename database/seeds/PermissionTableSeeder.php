<?php
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            // roles
           ['roles list' ,'roles.list'],
           ['roles create' ,'roles.create'],
           ['roles edit' ,'roles.edit'],
           ['roles delete' ,'roles.delete'],
           ['roles show' ,'roles.show'],

            // users
            ['users list' ,'users.list'],
            ['users create' ,'users.create'],
            ['users edit' ,'users.edit'],
            ['users delete' ,'users.delete'],

        ];
        foreach ($permissions as $permission) {
            Permission::create([
                'title' => $permission[0],
                'name' => $permission[1],
            ]);
        }
    }
}
