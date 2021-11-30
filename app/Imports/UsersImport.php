<?php
namespace App\Imports;
use App\Models\Training\Attendant;
use App\User;
// use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;

class UsersImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function collection(Collection $rows)
    {

        foreach ($rows as $row)
        {
            $data = json_encode([
                'en'=>$row['firstname'].' '.$row['lastname'],
                'ar'=>$row['firstname'].' '.$row['lastname'],
            ], JSON_UNESCAPED_UNICODE);

            $gender = 43;
            if($row['gender'] == 'female')
                $gender = 44;

            $expire_date = null;
            if($row['deactivation_date'] != '')
            {
                $row['deactivation_date'] = str_replace('/','-',$row['deactivation_date']);
                $expire_date = date('Y-m-d H:i:s', strtotime($row['deactivation_date']));
            }

            DB::table('users')->insert([
                [

                 'name' => $data,
                 'username_lms'=>$row['login'],
                 'email'=>$row['email'],
                //  'user_type'=>$user_type,
                 'reference_user_id' =>$row['user_id'],
                 'bio'=>$row['bio'],
                 'locale'=>$row['language'],
                 'gender_id'=>$gender,
                 'expire_date'=> $expire_date,

                ],
            ]);

            $user_id = DB::getPdo()->lastInsertId();
            $role_id = 0;
            if($row['user_type'] == 'SuperAdmin' || $row['user_type']  == 'Admin-Type')
                $role_id = 1;
            elseif($row['user_type'] == 'Manager')
                $role_id = 4;
            elseif($row['user_type'] == 'Trainer-Type')
                $role_id = 2;
            elseif($row['user_type'] == 'Trainee-Type')
                $role_id = 3;

            DB::table('role_user')->insert([
                [

                    'user_id' => $user_id,
                    'role_id'=>$role_id,

                ],
            ]);
        }

    }


}

