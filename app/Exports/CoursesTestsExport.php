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

class CoursesTestsExport implements FromCollection, WithHeadings,WithTitle,ShouldAutoSize,WithStyles,WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($from)
    {
        $this->from = $from[0];
        $this->search_arr = $from[1];
    }


    public function collection()
    {
        $query = $this->from;

        $select = " select  distinct contents.title as content_title,c2.title as section ,(select count(DISTINCT status,user_id) from user_exams where status= 1 and  exam_id= exams.id) as completed ,
        (select count(DISTINCT status,user_id) from user_exams where status= 1 and  exam_id= exams.id and mark >= (exams.pass_mark/100*exams.exam_mark)) as passess ".$query;

        return collect(DB::select($select, $this->search_arr));

    }

    public function headings(): array
    {
        return ["Section","Test","Completed","Passes"];
    }
    public function title(): string
    {
        return 'Tests';
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



