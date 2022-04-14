<?php

namespace App\Exports;
use App\Models\Training\Cart;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromQuery;
use Illuminate\Support\Facades\DB;

class AssessmentExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($sql=null,$course_id,$session_id)
    {
        $this->sql = $sql;
        $this->course_id = $course_id;
        $this->session_id = $session_id;
        // dd($this->session_id);
    }


    public function collection()
    {
        $query = $this->sql;
        // dd($query);
        $branch_id = getCurrentUserBranchData()->branch_id;

        $select = " select pre.name,pre.email , pre.mark as pre_assessment_score, post.mark, if(pre.mark<post.mark,'Improved',if(pre.mark=post.mark,'Constant','Deceased')) knowledge_status , pre.attendance_count,trainer.name trainer_name ,pre.s_id SID ".$query;

        // dd($select);
        if( $this->session_id == '')
        {
            return collect( DB::select($select, [$this->course_id, $this->course_id,$branch_id,512,$branch_id,513,514,$this->course_id,$this->course_id,$branch_id,512,$branch_id,514,511,$branch_id,$branch_id]));
        }
        else
        {
            return collect( DB::select($select, [$this->course_id, $this->course_id,$this->session_id,$branch_id,512,$branch_id,513,514,$this->course_id,$this->course_id,$this->session_id,$branch_id,512,$branch_id,514,511,$branch_id,$this->session_id,$branch_id]));
        }

    }

    public function headings(): array
    {
        return ["User","Email","Pre Assessment Score","Post Assessment Score","Knowledge Status","Number of attendance days","instructor","SID"];
    }
}



