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
use App\Imports\QuestionsLittleImport;
use App\Imports\QuestionsMoodleImport;
use App\Imports\QuestionsCourseImport;


use App\Imports\ResultsImport;
use App\Imports\CoursesImport;
use App\Imports\UsersImport;
use App\Imports\UsersCoursesImport;
use App\Imports\UsersGroupsImport;

use Maatwebsite\Excel\Facades\Excel;
use ReflectionFunctionAbstract;

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
        // dd(request()->all());
        if(request()->import_type > 0)
        {

            $class_name = Constant::where('id',request()->import_type)->first();

            if(request()->flag=="import")
                return $this->import(new $class_name->excerpt);
            elseif(request()->flag=="sample")
                return response()->download($class_name->slug);

        }

        Active::Flash('Not Imported', __('flash.choose_mporot_type'), 'danger');
        return back();

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

  // public function importQuestionsLittle()
    // {
    //     return $this->import(new QuestionsLittleImport);
    // }

    // public function importQuestionsMoodle()
    // {
    //     return $this->import(new QuestionsMoodleImport);
    // }


    // public function importResults()
    // {
    //     // dd(request()->all());

    //     return $this->import(new ResultsImport);
    // }
    // public function importQuestionsCourse()
    // {
    //     return $this->import(new QuestionsCourseImport);
    // }


}
