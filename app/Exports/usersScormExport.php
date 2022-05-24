<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromQuery;
use Illuminate\Support\Facades\DB;

class usersScormExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // $from,$course_id,$user_id, 512
    public function __construct($from=null, $course_id,$user_id)
    {
        $this->from = $from;
        $this->course_id = $course_id;
        $this->user_id = $user_id;
    }

    public function collection()
    {

        $query = $this->from;
        $select = " select JSON_UNQUOTE(JSON_EXTRACT(courses.title,'$.en')) as course_title,contents.title as content_title, scormvars_master.date,scormvars_master.lesson_status, scormvars_master.score ".$query;
        // dd($select);
        $branch_id = getCurrentUserBranchData()->branch_id;


        if(!is_null($this->course_id) )
            return collect(DB::select($select, [ $this->user_id,$branch_id, $this->course_id] ));
        else
            return collect(DB::select($select, [$this->user_id,$branch_id] ));
    }

    public function headings(): array
    {
        return ["Course","Scorm","Date","Progress","Score"];
    }
}



