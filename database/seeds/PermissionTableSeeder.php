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
           ['list roles' ,'training.roles.index'],
           ['create roles' ,'roles.create'],
           ['edit role' ,'roles.edit'],
           ['delete roles' ,'roles.delete'],
           ['restore roles' ,'roles.restore'],

            // users
            ['list users' ,'training.users.index'],
            ['create users' ,'users.create'],
            ['edit users' ,'users.edit'],
            ['delete users' ,'users.delete'],
            ['restore users' ,'users.restore'],


            // course
            ['list course' ,'training.courses.index'],
            ['create course' ,'course.create'],
            ['edit course' ,'course.edit'],
            ['delete course' ,'course.delete'],
            ['restore course' ,'course.restore'],


            // course contents
            ['course contents' ,'course.contents.list'],

            // course units
            ['course units' ,'course.units.list'],

            // course units
            ['course users list' ,'course.users.list'],

            // Course Role Path
            ['role path' ,'rolePath.list'],

            // certificates
            ['certificates' ,'training.certificates.index'],


            // scorms
            ['scorms' ,'training.scormsReportOverview'],


            // categories
            ['categories' ,'training.categories.index'],

            // preview exam edit
            ['edit preview exam' ,'preview.exam.edit'],

        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'title' => $permission[0],
                'name' => $permission[1],
            ]);
        }
    }
}
