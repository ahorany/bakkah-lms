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
    public function __construct($sql=null,$course_id)
    {
        $this->sql = $sql;
        $this->course_id = $course_id;
    }


    public function collection()
    {
        $query = $this->sql;
        // dd($this->course_id);
        $branch_id = getCurrentUserBranchData()->branch_id;
        $select = " select JSON_UNQUOTE(JSON_EXTRACT(users.name, '$.en')) as User , concat(courses_registration.progress,' %') as progress  ,
        if(roles.role_type_id = 511,'Instructor','Learner')  as role_type_id,sessions.date_from  as date_from  ,sessions.date_to as date_to ".$query;

        return collect(DB::select($select, [$branch_id,$branch_id, $this->course_id]));
    }

    public function headings(): array
    {
        return ["User","progress","role type","Date From","Date To"];
    }
}



