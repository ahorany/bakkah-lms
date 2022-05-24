<?php

namespace App\Exports;
use App\Models\Training\Cart;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromQuery;
use Illuminate\Support\Facades\DB;

class CoursesTestsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($from=null,$course_id)
    {
        $this->from = $from;
        $this->course_id = $course_id;
    }


    public function collection()
    {
        $query = $this->from;

        $select = " select  contents.title as content_title,c2.title as section ,(select count(DISTINCT status,user_id) from user_exams where status= 1 and  exam_id= exams.id) as completed ,
        (select count(DISTINCT status,user_id) from user_exams where status= 1 and  exam_id= exams.id and mark >= (exams.pass_mark/100*exams.exam_mark)) as passess ".$query;
        return collect(DB::select($select, [ $this->course_id]));
    }

    public function headings(): array
    {
        return ["Section","Test","Completed","Passes"];
    }
}



