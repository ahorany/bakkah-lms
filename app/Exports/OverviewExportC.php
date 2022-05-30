<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromQuery;
use Illuminate\Support\Facades\DB;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class OverviewExportC implements FromCollection,WithEvents,WithTitle,ShouldAutoSize,WithStyles
{
    public function __construct($course_id,$assigned_learners,$completed_learners,$learners_in_progress,$learners_not_started,$assigned_instructors)
    {
        $this->course_id = $course_id;
        $this->assigned_learners = $assigned_learners;
        $this->completed_learners = $completed_learners;
        $this->learners_in_progress = $learners_in_progress;
        $this->learners_not_started = $learners_not_started;
        $this->assigned_instructors = $assigned_instructors;
    }

     public function collection()
    {

        $branch_id = getCurrentUserBranchData()->branch_id;

        $sql_d = "select name from branches where id = ? ";
        $branch = DB::select($sql_d,[$branch_id]);


        $sql_course  = " select JSON_UNQUOTE(JSON_EXTRACT(title, '$.en')) as name,category_id,created_at,PDUs
                    from courses
                    where id = ?  ";
        $course = DB::select($sql_course,[$this->course_id]);

        $sql_categ  = " select JSON_UNQUOTE(JSON_EXTRACT(title, '$.en')) as title
                        from categories  where id = ?  ";
        $category = DB::select($sql_categ,[ $course[0]->category_id ]);


        $all = [];
        $info['info'] = 'Report information';
        $domain['domain']  = 'Domain' ;
        $domain['domain_v']  = $branch[0]->name ;
        $type['type'] = 'Report type' ;
        $type['type_v'] = 'Course report' ;
        $ex_date['ex_date'] = 'Export date' ;
        $ex_date['ex_date_v'] = now();

        $u_info['u_info'] = 'Course information';
        $u_name['u_name'] = ' Course name';
        $u_name['u_name_v'] = $course[0]->name;
        $cat['cat'] = 'Category';
        $cat['cat_v'] =  $category[0]->title;
        $u_t['u_t'] = 'Creation date';
        $u_t['u_t_v'] =  $course[0]->created_at;
        $pdu['pdu'] = 'pdu';
        $pdu['pdu_v'] =  $course[0]->PDUs;


        $activity['activity'] = ' Training activity';
        $assigned['assigned'] = 'Assigned learners';
        $assigned['assigned_v'] =  $this->assigned_learners;
        $completed['completed'] = 'Completed learners';
        $completed['completed_v'] =   $this->completed_learners;
        $progress['progress'] = 'Learners in progress';
        $progress['progress_v'] =    $this->learners_in_progress;
        $started['started'] = 'Learners not started';
        $started['started_v'] =   $this->learners_not_started;
        $Instructors['Instructors'] = 'Instructors';
        $Instructors['Instructors_v'] = $this->assigned_instructors;


        $all[0] = $info;$all[1] = $domain;$all[2] = $type;$all[3] = $ex_date;
        $all[4] = $u_info;$all[5] = $u_name;$all[6] = $cat;$all[7] = $u_t;$all[8] = $pdu;
        $all[9] = $activity;$all[10] = $assigned;$all[11] = $completed;$all[12] = $progress;$all[13] = $started;$all[14] = $Instructors;



        $final = collect($all);
        // dd($final);

        return $final;

    }

    public function registerEvents(): array
    {

        return [
            AfterSheet::class    => function(AfterSheet $event)
            {

                    $cellRange = 'A1:B1';
                    //    $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setName('Calibri');
                    $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);


                    $cellRange = 'A5:B5';
                    $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);

                    $cellRange = 'A10:B10';
                    $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);

                    $event->sheet->getDelegate()->getStyle('A1:B1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('99ebff');
                    $event->sheet->getDelegate()->getStyle('A5:B5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('99ebff');
                    $event->sheet->getDelegate()->getStyle('A10:B10')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('99ebff');
            },
            ];
   }

   public function styles(Worksheet $sheet)
    {
        return [


            // Styling a specific cell by coordinate.
            'A1' => ['font' => ['italic' => true,'background-color'=>'green','bold' => true]],
            'A5' => ['font' => ['italic' => true,'background-color'=>'green','bold' => true]],
            'A10' => ['font' => ['italic' => true,'background-color'=>'green','bold' => true]],


        ];
    }

   public function title(): string
   {
       return 'Overview';
   }

}



