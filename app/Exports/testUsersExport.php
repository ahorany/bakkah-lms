<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromQuery;
use Illuminate\Support\Facades\DB;

class testUsersExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // $from,$course_id,$user_id, 512
    public function __construct($from=null, $exam_id)
    {
        $this->from = $from;
        $this->exam_id = $exam_id;
    }

    public function collection()
    {

        $query = $this->from;
        $select = " select user_branches.name as user_name,users.email,time,if(ue1.mark >= (exams.pass_mark/100*exams.exam_mark),'Pass','Fail') as result,concat(ue1.mark*100/exams.exam_mark,' %') as score ".$query;
        // dd($select);
        $branch_id = getCurrentUserBranchData()->branch_id;

        // $users = DB::select($sql,[getCurrentUserBranchData()->branch_id,$exam_id,1,$exam_id,1]);
        return collect(DB::select($select, [$branch_id,$this->exam_id,1,$this->exam_id,1] ));

    }

    public function headings(): array
    {
        return ["User","Email","Date","Result","Score"];
    }
}



