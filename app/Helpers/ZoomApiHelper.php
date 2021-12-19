<?php

namespace App\Helpers;

class ZoomApiHelper {

    public static function createZoomMeeting($meetingConfig = []){

        // $jwtToken = config('zoom.ZOOM_API_TOKEN');
        $jwtToken = 'eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOm51bGwsImlzcyI6Ik5keTdVX1hHUkFhTkU1LWtSZTZhM1EiLCJleHAiOjE2NDAxNzg1NzMsImlhdCI6MTYzOTU3Mzc3Mn0.ZcmmvWHkJMvy7uLHn-f7VgftqCeA0184yscP4BDeezU';

        $requestBody = [
            'topic'			=> $meetingConfig['topic'] 		?? 'PHP General Talk',
            'type'			=> $meetingConfig['type'] 		?? 2,
            'start_time'	=> $meetingConfig['start_time']	?? date('Y-m-dTh:i:00').'Z',
            'duration'		=> $meetingConfig['duration'] 	?? 30,
            'password'		=> $meetingConfig['password'] 	?? mt_rand(),
            'timezone'		=> 'Africa/Cairo',
            'agenda'		=> 'PHP Session',
            'settings'		=> [
                'host_video'			=> false,
                'participant_video'		=> true,
                'cn_meeting'			=> false,
                'in_meeting'			=> false,
                'join_before_host'		=> true,
                'mute_upon_entry'		=> true,
                'watermark'				=> false,
                'use_pmi'				=> false,
                'approval_type'			=> 1,
                'registration_type'		=> 1,
                'audio'					=> 'voip',
                'auto_recording'		=> 'none',
                'waiting_room'			=> false
            ]
        ];

        // $zoomUserId = config('settings.ZOOM_API_USER_ID');
        $zoomUserId = 'EDJCEPg1R_6UonQp4djmkQ';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // Skip SSL Verification
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.zoom.us/v2/users/".$zoomUserId."/meetings",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($requestBody),
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer ".$jwtToken,
                "Content-Type: application/json",
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);


        if ($err) {
            return [
                'success' 	=> false,
                'msg' 		=> 'cURL Error #:' . $err,
                'response' 	=> ''
            ];
        } else {
            return [
                'success' 	=> true,
                'msg' 		=> 'success',
                'response' 	=> json_decode($response)
            ];
        }
    }
}

//
//{
//    "topic": "Akkalkot Darshan",
//  "type": 8,
//  "start_time": "2020-06-26T14:30:00Z",
//  "duration": 60,
//  "password": "123abc",
//  "recurrence": {
//    "type": "1",
//    "repeat_interval": "1",
//    "weekly_days": "4",
//    "monthly_day": 30,
//    "monthly_week": "4",
//    "monthly_week_day": "4",
//    "end_times": 1,
//    "end_date_time": "2020-07-04T16:20:00Z"
//  },
//  "settings": {
//    "host_video": true,
//    "participant_video": true,
//    "cn_meeting": false,
//    "in_meeting": false,
//    "join_before_host": false,
//    "mute_upon_entry": false,
//    "watermark": false,
//    "use_pmi": false,
//    "approval_type": 0,
//    "registration_type": 1,
//    "audio": "both",
//    "auto_recording": "none",
//    "alternative_hosts": "",
//    "close_registration": true,
//    "waiting_room": true,
//    "contact_name": "Umesh",
//    "contact_email": "abc@gmail.com",
//    "registrants_email_notification": true,
//    "meeting_authentication": true,
//    "authentication_option": "",
//    "authentication_domains": ""
//  }
//}
