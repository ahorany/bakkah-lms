<?php

namespace App\Exports;
use App\Models\Training\Cart;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromQuery;
use Illuminate\Support\Facades\DB;

class UsersExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($sql=null,$course_id,$training_option_id)
    {
        $this->sql = $sql;
        $this->course_id = $course_id;
        $this->training_option_id = $training_option_id;
    }


    public function collection()
    {
        $query = $this->sql;
        // dd($this->course_id);
        $branch_id = getCurrentUserBranchData()->branch_id;
        if($this->training_option_id == 13)//live
        {
            $select = " select JSON_UNQUOTE(JSON_EXTRACT(users.name, '$.en')) as User , concat(courses_registration.progress,' %') as progress  ,
            if(roles.role_type_id = 511,'Instructor','Learner')  as role_type_id,sessions.date_from  as date_from  ,sessions.date_to as date_to ,courses_registration.created_at as enrolled_date ,users.last_login as last_login".$query;
        }
        else
        {
            $select = " select JSON_UNQUOTE(JSON_EXTRACT(users.name, '$.en')) as User , concat(courses_registration.progress,' %') as progress  ,
            if(roles.role_type_id = 511,'Instructor','Learner')  as role_type_id ,courses_registration.created_at as enrolled_date ,users.last_login as last_login".$query;
        }


        return collect(DB::select($select, [$branch_id,$branch_id, $this->course_id]));
    }

    public function headings(): array
    {
        if($this->training_option_id == 13)//live
        {
            return ["User","progress","role type","Session Date From","Session Date To","Enrolled Date" , "Last Login"];
        }
        else
        {
            return ["User","progress","role type","Enrolled Date","Last Login"];
        }
    }
}



