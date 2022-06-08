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

class usersScormExport implements FromCollection, WithHeadings,WithTitle,ShouldAutoSize,WithStyles,WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // $from,$course_id,$user_id, 512
    public function __construct($from)
    {
        $this->from = $from[0];
        $this->search_arr = $from[1];

    }

    public function collection()
    {

        $query = $this->from;
        $select = " select JSON_UNQUOTE(JSON_EXTRACT(courses.title,'$.en')) as course_title,contents.title as content_title, scormvars_master.date,scormvars_master.lesson_status, scormvars_master.score ".$query;
        // dd($select);
        return collect(DB::select($select,$this->search_arr  ));
    }

    public function headings(): array
    {
        return ["Course","Scorm","Date","Progress","Score"];
    }

    public function title(): string
    {
        return 'SCORMS';
    }

    public function registerEvents(): array
    {

        return [
            AfterSheet::class    => function(AfterSheet $event)
            {

                    $cellRange = 'A1:E1';
                    //    $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setName('Calibri');
                    $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);

                    $event->sheet->getDelegate()->getStyle('A1:E1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('99ebff');

            },
            ];
   }

   public function styles(Worksheet $sheet)
   {
       return [


           // Styling a specific cell by coordinate.
           'A1:E1' => ['font' => ['italic' => true,'bold' => true]],
       ];
   }

}



