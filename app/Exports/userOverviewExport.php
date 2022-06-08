<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromQuery;
use Illuminate\Support\Facades\DB;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;


// class userOverviewExport implements FromCollection, WithHeadings
class userOverviewExport implements WithMultipleSheets
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct($user_id,$complete_courses_no,$from_course,$from_test,$from_scorm,$assigned_courses)
    {
        $this->user_id = $user_id;
        $this->complete_courses_no = $complete_courses_no;
        $this->from_course = $from_course;
        $this->from_test = $from_test;
        $this->from_scorm = $from_scorm;
        $this->assigned_courses = $assigned_courses;
    }

    public function sheets(): array
    {

        return [
            new OverviewExportU($this->user_id,$this->complete_courses_no,$this->assigned_courses),
            new CoursesExport($this->from_course),
            new usersTestsExport($this->from_test),
            new usersScormExport($this->from_scorm),
        ];
    }

}



