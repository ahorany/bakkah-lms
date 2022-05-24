<?php

namespace App\Exports;
use App\Models\Training\Cart;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromQuery;
use Illuminate\Support\Facades\DB;

class CoursesScormsExport implements FromCollection, WithHeadings
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
        $select = " select  i.sestion,i.title,i.attempts,other.passess ".$query;
        return collect(DB::select($select, [ $this->course_id,$this->course_id]));
    }

    public function headings(): array
    {
        return ["Section","Scorm","Attempts","Passes"];
    }
}



