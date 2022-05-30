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

class AssessmentExport implements FromCollection, WithHeadings,WithTitle,ShouldAutoSize,WithStyles,WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($sql=null,$course_id,$session_id,$training_option_id,$user_id,$show_all)
    {
        $this->sql = $sql;
        $this->course_id = $course_id;
        $this->session_id = $session_id;
        $this->training_option_id = $training_option_id;
        $this->user_id = $user_id;
        $this->show_all = $show_all;
        // dd($this->session_id);
    }


    public function collection()
    {
        $query = $this->sql;
        // dd($query);
        $branch_id = getCurrentUserBranchData()->branch_id;
        if($this->training_option_id == 13)//live
        {
            $select = " select pre.name,pre.email , pre.mark as pre_assessment_score, post.mark, if(post.mark is Null or post.mark = '','Not Yet', if(pre.mark<post.mark,'Improved',if(pre.mark=post.mark,'Constant','Deceased'))) knowledge_status , pre.attendance_count,trainer.name trainer_name ,pre.s_id SID ".$query;
        }
        else
        {
            $select = " select pre.name,pre.email , pre.mark as pre_assessment_score, post.mark,  if(post.mark is Null or post.mark = '','Not Yet',if(pre.mark<post.mark,'Improved',if(pre.mark=post.mark,'Constant','Deceased'))) knowledge_status  ".$query;
        }

        // dd($select);
        if( $this->session_id == '')
        {
            if(!is_null($this->user_id) && $this->show_all == 0)
            {
                return collect( DB::select($select, [$this->course_id, $this->course_id,$this->user_id,$branch_id,512,$branch_id,513,514,$this->course_id,$this->course_id,$branch_id,512,$branch_id,514,511,$branch_id,$branch_id]));
            }
            else
            {
                return collect( DB::select($select, [$this->course_id, $this->course_id,$branch_id,512,$branch_id,513,514,$this->course_id,$this->course_id,$branch_id,512,$branch_id,514,511,$branch_id,$branch_id]));
            }
        }
        else
        {
            if(!is_null($this->user_id) && $this->show_all == 0)
            {
                return collect( DB::select($select, [$this->course_id, $this->course_id,$this->session_id,$this->user_id,$branch_id,512,$branch_id,513,514,$this->course_id,$this->course_id,$this->session_id,$branch_id,512,$branch_id,514,511,$branch_id,$this->session_id,$branch_id]));
            }
            else
            {
                return collect( DB::select($select, [$this->course_id, $this->course_id,$this->session_id,$branch_id,512,$branch_id,513,514,$this->course_id,$this->course_id,$this->session_id,$branch_id,512,$branch_id,514,511,$branch_id,$this->session_id,$branch_id]));
            }
        }

    }

    public function headings(): array
    {
        if($this->training_option_id == 13)//live
        {
            return ["User","Email","Pre Assessment Score","Post Assessment Score","Knowledge Status","Number of attendance days","instructor","SID"];
        }
        else
        {
            return ["User","Email","Pre Assessment Score","Post Assessment Score","Knowledge Status"];
        }
    }

    public function title(): string
    {
        return 'Assessments';
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



