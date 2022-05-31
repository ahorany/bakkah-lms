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

class scormUsersExport implements FromCollection, WithHeadings,WithTitle,ShouldAutoSize,WithStyles,WithEvents
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

    public function title(): string
    {
        return 'SCORM Users';
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



