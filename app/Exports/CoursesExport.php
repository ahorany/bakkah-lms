<?php

namespace App\Exports;
use App\Models\Training\Cart;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromQuery;
use Illuminate\Support\Facades\DB;

class CoursesExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($sql=null, $user_id,$course_id,$show_all)
    {
        $this->sql = $sql;
        $this->user_id = $user_id;
        $this->course_id = $course_id;
        $this->show_all = $show_all;
    }

    public function collection()
    {
        $query = $this->sql;

        $select = " select JSON_UNQUOTE(JSON_EXTRACT(courses.title, '$.en')) as Course , concat(courses_registration.progress,' %') as progress  ,
        courses_registration.score,courses.created_at,courses.PDUs ".$query;
        // dd($select);
        $branch_id = getCurrentUserBranchData()->branch_id;
        if(!is_null($this->course_id) && $this->show_all == 0)
            return collect(DB::select($select, [$branch_id,512, $branch_id,$this->course_id, $branch_id, $this->user_id] ));
        else
            return collect(DB::select($select, [$branch_id,512, $branch_id, $branch_id, $this->user_id] ));
    }

    public function headings(): array
    {
        return ["Course","progress","score","created at","PDUs"];
    }
}



