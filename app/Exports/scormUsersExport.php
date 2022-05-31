<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromQuery;
use Illuminate\Support\Facades\DB;

class scormUsersExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // $from,$course_id,$user_id, 512
    public function __construct($from=null, $content_id)
    {
        $this->from = $from;
        $this->content_id = $content_id;
    }

    public function collection()
    {
        $query = $this->from;
        $select = " select user_branches.name as user_name,users.email,scormvars_master.date,scormvars_master.score, scormvars_master.lesson_status ".$query;
        // dd($select);
        return collect(DB::select($select, [$this->content_id] ));

    }

    public function headings(): array
    {
        return ["User","Email","Date","Score","Progress"];
    }
}



