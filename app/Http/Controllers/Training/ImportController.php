<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Helpers\Active;
use App\Http\Requests\Training\CourseRequest;
use App\Models\Admin\Partner;
// use App\Models\Training\Course;
use App\Constant;
use App\Models\Training\Group;
use Illuminate\Database\Eloquent\Builder;
use App\Imports\QuestionsImport;
use App\Imports\CoursesImport;
use App\Imports\UsersImport;
use App\Imports\UsersCoursesImport;
use App\Imports\UsersGroupsImport;




use Maatwebsite\Excel\Facades\Excel;
// use Illuminate\Support\Str;

class ImportController extends Controller
{
    public function __construct()
    {
        Active::$namespace  = 'training';
        Active::$folder     = 'imports';
    }

    public function imports(){

        return Active::Index();
    }


    public function importCourses()
    {
        return $this->import(new CoursesImport);
    }

    public function importUsers()
    {
        return $this->import(new UsersImport);
    }
    public function importUsersCourses()
    {
        return $this->import(new UsersCoursesImport);
    }
    public function importUsersGroups()
    {
        return $this->import(new UsersGroupsImport);
    }


    public function importQuestions()
    {
        return $this->import(new QuestionsImport);
    }


    public function import($import)
    {
        if(request()->file('file') != '')
        {
             Excel::import($import,request()->file('file'));
             Active::Flash('Imported', __('flash.imported'), 'success');
        }
        else
             Active::Flash('Not Imported', __('flash.not_imported'), 'danger');

        return back();
    }


}
