<?php

namespace App\Exports;
use App\Models\Training\Cart;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromQuery;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;

class UsersExport implements FromCollection, WithHeadings,WithTitle,ShouldAutoSize,WithStyles,WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($sql=null,$course_id,$training_option_id,$user_id,$show_all)
    {
        $this->sql = $sql;
        $this->course_id = $course_id;
        $this->training_option_id = $training_option_id;
        $this->user_id = $user_id;
        $this->show_all = $show_all;
    }


    public function collection()
    {
        $query = $this->sql;
        // dd($this->course_id);
        $branch_id = getCurrentUserBranchData()->branch_id;
        if($this->training_option_id == 13)//live
        {
            // select sessions.date_from,sessions.date_to,constants.name as c_name ,courses.complete_progress,courses_registration.id as c_reg_id,courses_registration.created_at as enrolled_date ,users.last_login as last_login
            $select = " select user_branches.name as User ,users.email as email ,  if(roles.role_type_id=512,concat(courses_registration.progress,' %'),'' ) as progress  ,
            courses_registration.completed_at ,
            if(roles.role_type_id = 511,'Instructor','Learner')  as role_type_id,sessions.date_from  as date_from  ,sessions.date_to as date_to ,courses_registration.created_at as enrolled_date ,users.last_login as last_login ".$query;
        }
        else
        {
            $select = " select user_branches.name as User , users.email as email ,if(roles.role_type_id=512,concat(courses_registration.progress,' %'),'' ) as progress ,
            courses_registration.completed_at ,
            if(roles.role_type_id = 511,'Instructor','Learner')  as role_type_id ,courses_registration.created_at as enrolled_date ,users.last_login as last_login ".$query;
        }

        if(!is_null($this->user_id) && $this->show_all == 0)
            return collect(DB::select($select, [$branch_id,$branch_id, $this->course_id,$this->user_id]));
        else
            return collect(DB::select($select, [$branch_id,$branch_id, $this->course_id]));
    }

    public function headings(): array
    {
        if($this->training_option_id == 13)//live
        {
            return ["User","Email","Progress","Completion Date","Role Type","Session Date From","Session Date To","Enrolled Date" , "Last Login"];
        }
        else
        {
            return ["User","Email","Progress","Completion Date","Role Type","Enrolled Date","Last Login"];
        }
    }

    public function title(): string
    {
        return 'Users';
    }

    public function registerEvents(): array
    {

        return [
            AfterSheet::class    => function(AfterSheet $event)
            {

                $cellRange = 'A1:Z1';
                //    $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setName('Calibri');
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('A1:Z1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('99ebff');
            },
            ];
   }

   public function styles(Worksheet $sheet)
   {
       return [
           // Styling a specific cell by coordinate.
           'A1:Z1' => ['font' => ['italic' => true,'bold' => true]],
       ];
   }


}



