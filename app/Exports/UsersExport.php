<?php

namespace App\Exports;
use PhpOffice\PhpSpreadsheet\Cell\Hyperlink;
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
    public function __construct($sql,$training_option_id)
    {
        $this->sql = $sql[0];
        $this->training_option_id = $training_option_id;
        $this->search_arr = $sql[1];
    }


    public function collection()
    {
        $query = $this->sql;
        // dd($this->course_id);
        $branch_id = getCurrentUserBranchData()->branch_id;
        if($this->training_option_id == 13)//live
        {
            $select = " select user_branches.name as User ,users.email as email ,  if(roles.role_type_id=512,concat(courses_registration.progress,' %'),'' ) as progress  ,
            courses_registration.completed_at ,
            if(roles.role_type_id = 511,'Instructor','Learner')  as role_type_id,sessions.date_from  as date_from  ,sessions.date_to as date_to ,courses_registration.created_at as enrolled_date ,users.last_login as last_login,if(courses_registration.progress >= courses.complete_progress and courses_registration.progress != 0 ,concat('=HYPERLINK(\"https://learning.bakkah.com/en/training/certificates/certificate?course_registration_id=',courses_registration.id,'\",\"Show\")'),'-'
            ) as certificate ".$query;
        }
        else
        {
            $select = " select user_branches.name as User , users.email as email ,if(roles.role_type_id=512,concat(courses_registration.progress,' %'),'' ) as progress ,
            courses_registration.completed_at ,
            if(roles.role_type_id = 511,'Instructor','Learner')  as role_type_id ,courses_registration.created_at as enrolled_date ,users.last_login as last_login,if(courses_registration.progress >= courses.complete_progress and courses_registration.progress != 0,concat('=HYPERLINK(\"https://learning.bakkah.com/en/training/certificates/certificate?course_registration_id=',courses_registration.id,'\",\"Show\")'),'-'
                ) as certificate ".$query;
        }

        return collect(DB::select($select,  $this->search_arr));
    }

    public function headings(): array
    {
        if($this->training_option_id == 13)//live
        {
            return ["User","Email","Progress","Completion Date","Role Type","Session Date From","Session Date To","Enrolled Date" , "Last Login","Certificate"];
        }
        else
        {
            return ["User","Email","Progress","Completion Date","Role Type","Enrolled Date","Last Login","Certificate"];
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
                if($this->training_option_id == 13)//live
                {
                    $col = 'J';
                }
                else
                {
                    $col = 'H';
                }

                foreach ($event->sheet->getColumnIterator($col) as $row) {
                    foreach ($row->getCellIterator() as $cell) {

                        if (str_contains($cell->getValue(), '://')) {

                            $cell->setHyperlink(new Hyperlink($cell->getValue(), 'read'));

                            $event->sheet->getStyle($cell->getCoordinate())->applyFromArray([
                                'font' => [
                                    'color' => ['rgb' => '0000FF'],
                                    'underline' => 'single'
                                ]
                            ]);
                        }
                    }
                }

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



//    'https://learning.bakkah.com/en/training/certificates/certificate?course_registration_id=2210' as certificate

}



