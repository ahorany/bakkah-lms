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

class CoursesExport implements FromCollection, WithHeadings,WithTitle,ShouldAutoSize,WithStyles,WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($sql=null, $user_id,$course_id,$show_all)
    {
        $this->sql = $sql;
        $this->user_id = $user_id;
        $this->course_id = $course_id;
        $this->show_all = $show_all;
    }

    public function collection()
    {
        $query = $this->sql;

        $select = " select JSON_UNQUOTE(JSON_EXTRACT(courses.title, '$.en')) as Course , concat(courses_registration.progress,' %') as progress  ,courses_registration.created_at,courses_registration.completed_at,courses.PDUs ".$query;
        // dd($select);
        $branch_id = getCurrentUserBranchData()->branch_id;

        if(!is_null($this->course_id) &&  $this->show_all == 0)
            return collect(DB::select($select, [$branch_id,512, $branch_id,$this->course_id, $branch_id, $this->user_id] ));
        else
            return collect(DB::select($select, [$branch_id,512, $branch_id, $branch_id, $this->user_id] ));
    }

    public function headings(): array
    {
        return ["Course","progress","Enrolled On","Completion Date","PDUs"];
    }

    public function title(): string
    {
        return 'Courses';
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



