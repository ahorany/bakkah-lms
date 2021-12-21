<?php
namespace App\Imports;
use App\Models\Training\Course;
use App\User;
// use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;

class UsersGroupsImport implements ToCollection, WithHeadingRow
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

            $user = User::where('username_lms',$row['usertogroups'])->first();

            DB::table('user_groups')->insert([
                [
                    'user_id' => $user->id,
                    'group_id'=>$row['group_id'],
                ],
            ]);

        }
    }


}

