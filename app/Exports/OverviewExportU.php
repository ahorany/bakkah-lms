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


class OverviewExportU implements FromCollection,WithEvents,WithTitle,ShouldAutoSize,WithStyles
{
    public function __construct($user_id,$complete_courses_no,$assigned_courses)
    {
        $this->user_id = $user_id;
        $this->complete_courses_no = $complete_courses_no;
        $this->assigned_courses = $assigned_courses;
    }

     public function collection()
    {

        $branch_id = getCurrentUserBranchData()->branch_id;

        $sql_d = "select name from branches where id = ? ";
        $branch = DB::select($sql_d,[$branch_id]);

        $sql_ub  = " select name,created_at,expire_date
                    from user_branches
                    where user_id = ?  and branch_id = ?";
        $user_b = DB::select($sql_ub,[$this->user_id,$branch_id]);


        $sql_u  = " select email,last_login from users  where id = ?  ";
        $user = DB::select($sql_u,[$this->user_id]);

        $sql_t = "select name from roles where id = (select role_id from  model_has_roles where model_id = ? and model_type = ? ) ";
        $user_t = DB::select($sql_t,[$this->user_id,'App\\User']);

        $all = [];
        $info['info'] = 'Report information';
        $domain['domain']  = 'domain' ;
        $domain['domain_v']  = $branch [0]->name ;
        $type['type'] = 'Report type' ;
        $type['type_v'] = 'User report' ;
        $ex_date['ex_date'] = 'Export date' ;
        $ex_date['ex_date_v'] = now();

        $u_info['u_info'] = ' User information';
        $u_name['u_name'] = ' User name';
        $u_name['u_name_v'] = $user_b[0]->name;
        $email['email'] = 'Email address';
        $email['email_v'] =  $user[0]->email;
        $u_t['u_t'] = 'User type';
        $u_t['u_t_v'] =  $user_t[0]->name;
        $registration_date['registration_date'] = 'Registration date';
        $registration_date['registration_date_v'] =  $user_b[0]->created_at;
        $login['login'] = 'Last login';
        $login['login_v'] =  $user[0]->last_login;
        $expiration['expiration'] = 'Expiration date';
        $expiration['expiration_v'] =  $user_b[0]->expire_date;

        $activity['activity'] = ' Training activity';
        $assigned['assigned'] = 'Assigned courses';
        $assigned['assigned_v'] =  $this->assigned_courses;
        $completed['completed'] = 'Completed courses';
        $completed['completed_v'] =   $this->complete_courses_no;


        $all[0] = $info;$all[1] = $domain;$all[2] = $type;$all[3] = $ex_date;
        $all[4] = $u_info;$all[5] = $u_name;$all[6] = $email;$all[7] = $u_t;$all[8] = $registration_date;$all[9] = $login;$all[10] = $expiration;
        $all[11] = $activity;$all[12] = $assigned;$all[13] = $completed;



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

                    $cellRange = 'A12:B12';
                    $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);

                    $event->sheet->getDelegate()->getStyle('A1:B1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('99ebff');
                    $event->sheet->getDelegate()->getStyle('A5:B5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('99ebff');
                    $event->sheet->getDelegate()->getStyle('A12:B12')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('99ebff');
            },
            ];
   }

   public function styles(Worksheet $sheet)
    {
        return [


            // Styling a specific cell by coordinate.
            'A1' => ['font' => ['italic' => true,'background-color'=>'green','bold' => true]],
            'A5' => ['font' => ['italic' => true,'background-color'=>'green','bold' => true]],
            'A12' => ['font' => ['italic' => true,'background-color'=>'green','bold' => true]],


        ];
    }

   public function title(): string
   {
       return 'Overview';
   }

}



