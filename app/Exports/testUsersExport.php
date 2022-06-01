<?php

namespace App\Exports;
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

class testUsersExport implements FromCollection, WithHeadings,WithTitle,ShouldAutoSize,WithStyles,WithEvents
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

    public function title(): string
    {
        return 'Test Users';
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



