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


class CoursesScormsExport implements FromCollection, WithHeadings,WithTitle,ShouldAutoSize,WithStyles,WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($from=null,$course_id,$user_id,$show_all)
    {
        $this->from = $from;
        $this->course_id = $course_id;
        $this->user_id = $user_id;
        $this->show_all = $show_all;
    }


    public function collection()
    {
        $query = $this->from;
        $select = " select  i.sestion,i.title,i.attempts,other.passess ".$query;
        if(!is_null($this->user_id) && $this->show_all == 0)
        {
            return collect(DB::select($select, [ $this->course_id,$this->course_id,$this->course_id,$this->user_id]));
        }
        else
        {
            return collect(DB::select($select, [ $this->course_id,$this->course_id]));

        }
    }

    public function headings(): array
    {
        return ["Section","Scorm","Attempts","Passes"];
    }

    public function title(): string
    {
        return 'Scorms';
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



