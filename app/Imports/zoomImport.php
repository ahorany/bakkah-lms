<?php
namespace App\Imports;

use App\Models\Training\AttendenceZoomMaster;
use App\Models\Training\AttendenceZoomDetails;

use App\Models\Training\Cart;
use App\Models\Training\Session;

use App\Models\Training\Attendant;
use App\User;
// use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;

class ZoomImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {

        // dd(request()->session_id);
        // dd($count);
        $all_users = Cart::where('session_id',request()->session_id)->whereNotNull('user_id')->pluck('user_id')->toArray();
        $all_emails = User::whereIn('id',$all_users)->pluck('email')->toArray();
        // dump($all_emails);
        $i = 0;
        foreach ($rows as $row)
        {

            if($i == 0)
            {
               $count = AttendenceZoomMaster::where('session_id',request()->session_id)
                                    ->where('start_time',date('Y-m-d H:i:s', strtotime($row['start_time'])))
                                    ->where('meeting_id',$row['meeting_id'])
                                    ->count();

                if($count > 0)
                {
                    return false;
                }

                $attend_day = AttendenceZoomMaster::where('session_id',request()->session_id)
                                                    ->max('attend_day');
                $attend_day += 1;
                $meeting_id = $row['meeting_id'];
                $master_id =  AttendenceZoomMaster::create([
                    'meeting_id'        => $meeting_id,
                    'topic'             => $row['topic'],
                    'email'             => $row['user_email'],
                    'start_time'        => date('Y-m-d H:i:s', strtotime($row['start_time'])),
                    'end_time'          => date('Y-m-d H:i:s', strtotime($row['end_time'])),
                    'duration_minutes'  => $row['duration_minutes'],
                    'participants'      => $row['participants'],
                    'session_id'        => request()->session_id,
                    'attend_day'        => $attend_day,
                ]);


            }
            // dd( $master_id->id);
            // Name (Original Name)	User Email	Total Duration (Minutes)	Guest
            if($i>2)
            {
                if($row['end_time'] == 'Yes')
                    $guest = 1;
                else
                    $guest = 0;
                
                if($row['start_time'] >= 60)
                {
                    $is_attended = 1;
                    $absent_reason = '';
                }
                else
                {
                    $is_attended = 0;
                    $absent_reason = 'Less than 60 minutes';
                }

                AttendenceZoomDetails::create([
                    'master_id'                 => $master_id->id,
                    'name'                      => $row['meeting_id'],
                    'email'                     => $row['topic'],
                    'total_duration_minutes'    => $row['start_time'],
                    'guest'                     =>  $guest,
                    'is_attended'               => $is_attended,
                    'absent_reason'             => $absent_reason,
                ]);

                
                if (($key = array_search($row['topic'], $all_emails)) !== false) {
                    unset($all_emails[$key]);
                }
                
            }					
            $i++;
        }
        foreach($all_emails as $em)//absent pepole
        {
            $user_name = User::where('email',$em)->pluck('name')->first();
            $json = json_decode($user_name, true);

            AttendenceZoomDetails::create([
                'master_id'                 => $master_id->id,
                'name'                      => $json['en'],
                'email'                     => $em,
                'total_duration_minutes'    => 0,
                'guest'                     =>  1,
                'is_attended'               => 0,
                'absent_reason'             => 'Uknown',
            ]);
        }
        if(isset($master_id))
        {
            $emails = AttendenceZoomDetails::where('master_id',$master_id->id)->pluck('email')->toArray();
            // dump($master_id->id);  
            // dump($emails);
            $users = User::whereIn('email',$emails)->pluck('id')->toArray();
            // dump($users);
            $carts = Cart::whereIn('user_id',$users)->where('session_id',request()->session_id)->pluck('id')->toArray();
            

            // dump($all_users);

            foreach($carts as $cart)
            {
                // dd($attend_day);
                Attendant::create([
                    'cart_id' => $cart,
                    'attend_day'      => $attend_day,
                ]);

                Cart::where('id',  $cart)
                    ->update(['attendance_count' => DB::raw('attendance_count+1'), ]);
                $duration = Session::where('id',request()->session_id)->pluck('duration')->first();
                $attendance_count = Cart::where('id',$cart)->pluck('attendance_count')->first();
                if($duration > 0)
                    $progress = ($attendance_count / $duration) * 100;
                if($progress >= 80)
                {
                    Cart::where('id',  $cart)
                    ->update(['attend_type_id' => 454, ]);
                }

            }

        }

    }


}

