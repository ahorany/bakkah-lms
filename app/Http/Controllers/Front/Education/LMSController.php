<?php

namespace App\Http\Controllers\Front\Education;

use App\Helpers\Models\UserHelper;
use App\Models\Training\CartTrace;
use App\Http\Controllers\Controller;
use App\Mail\FailOnlineLms;
use App\Mail\FailSelfLms;
use App\Mail\MoodleLms;
use App\Mail\SelfLms;
use App\Models\Training\Cart;
use App\Models\Training\Payment;
use App\Models\Training\TrainingOption;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class LMSController extends Controller
{
    // public function self_submit(){

    //     $user_id = 1;
    //     if(request()->has('user_id')){
    //         $user_id = request()->user_id;
    //     }

    //     $payment_id = 1;
    //     if(request()->has('payment_id')){
    //         $payment_id = request()->user_id;
    //     }
    //     $user = User::find($user_id);
    //     $payment = Payment::find($payment_id);
    //     $lms_course_id = -1;
    //     if($payment->cart->course->id == 1){ // PMP
    //         $lms_course_id = 138;   // live = 131
    //     }elseif($payment->cart->course->id == 2){ // Prince2
    //         $lms_course_id = 139;   // live = 130
    //     }
    //     return $this->LMS_self($user, $payment, $lms_course_id);
    // }

    // public function online_submit(){
    //     $user_id = 1;
    //     if(request()->has('user_id')){
    //         $user_id = request()->user_id;
    //     }

    //     $payment_id = 1;
    //     if(request()->has('payment_id')){
    //         $payment_id = request()->user_id;
    //     }
    //     $user = User::find($user_id);
    //     $payment = Payment::find($payment_id);
    //     return $this->LMS_online($user, $payment);
    // }

    public function run($cart_master_id, $cart_id=null, $send_email=null){
        // dd($send_email);
        // if(env('LMS_WORK')==true)
        // if(env('NODE_ENV')=='production')
        {
            $carts = Cart::with('session.trainingOption')->where('master_id', $cart_master_id);
            if(!is_null($cart_id)){
                $carts = $carts->where('id', $cart_id);
            }
            $carts = $carts->get();

            foreach($carts as $cart)
            {
                if($cart->session->trainingOption->lms_course_id > 0 && !is_null($cart->session->trainingOption->lms_course_id)){
                    if($cart->session->trainingOption->lms_id==343){
                        return $this->LMS_self($cart, $send_email);
                    }
                    else if($cart->session->trainingOption->lms_id==342){
                        return $this->LMS_online($cart, $send_email);
                    }
                }
            }
            // if ($cart->session->trainingOption->constant->id == 11 && $cart->course_id != 32 && $cart->course_id != 35 && $cart->course_id != 36 && $cart->course_id != 37) {

            //     $tallent_array = [
            //         1 => 138,// live = 131 PMP
            //         2 => 139,// live = 130 Prince2
            //     ];
            //     $lms_course_id = $tallent_array[$cart->course->id] ?? -1;
            //     if ($lms_course_id != -1) {
            //         return $this->LMS_self($user, $cart, $lms_course_id);
            //     }
            // } else {//Online
                // $moodle_array = [
                //     2 => 75,
                //     1 => 78,
                //     3 => 84,
                //     4 => 100,
                //     5 => 16,
                //     6 => 81,
                //     7 => 104,
                //     19 => 76,
                //     20 => 97,
                //     21 => 95,
                //     22 => 96,
                //     12 => 79,
                //     13 => 94,
                //     14 => 83,
                //     15 => 93,
                //     16 => 80,
                //     23 => 85,
                //     24 => 99,
                //     32 => 103,
                //     35 => 111,
                //     36 => 112,
                //     37 => 113,
                // ];
                // $moodle_course_id = $moodle_array[$cart->course->id] ?? -1;
                // if ($moodle_course_id != -1) {
                //     return $this->LMS_online($user, $cart, $moodle_course_id);
                // }
            // }
        }
    }

    public function LMS_self($cart, $send_email=null){

        $cert_no  = date('Y').$cart->id;
        Cart::where('id', $cart->id)->update([
            'cert_no'=>$cert_no,
            'status_id'=>51,
        ]);
        // CartTrace::where('master_id', $cart->id)
        // ->latest()
        // ->first()
        // ->update([
        //     'status_id'=>51,
        // ]);

        $name = $cart->userId->en_name??null;
        if(is_null($name)){
            $arr = explode("@", $cart->userId->email, 2);
            $first = $arr[0];
            $name = $first . ' ' .  $cart->userId->id;
        }
        $full_name = $this->split_name($name);

        $course_title = $cart->course->en_title??null;
        $bio = $name.' SelfLms account created from Bakkah website in '.date("d-m-Y H:i:s A", strtotime('+3 hours')).' and enrolled in '.$course_title;

        $talent_user__array = $this->create_talentlms_account($cart, $full_name, $cert_no, $bio);
        // dd($talent_user);
        $talent_user = $talent_user__array['return_val'];
        $user_id__found = $talent_user__array['user_id__found'];

        if($talent_user){

            // ========================= ٍStart Simulation ==================================
            if($cart->exam_simulation_price > 0){
                $user_id__found = $moodle['user_id__found']??-1;
                $e_portal_username = $moodle['e_portal_username']??null;

                $training_option_id = $cart->trainingOption->exam_simulation_id;
                $training_option = TrainingOption::find($training_option_id);

                $moodle_lms_id = $training_option->lms_id??0;
                $moodle_exam_sim_id = $training_option->lms_course_id??0;

                if($moodle_lms_id==342 && $moodle_exam_sim_id > 0){
                    $moodle = $this->create_moodle_account($cart, $moodle_exam_sim_id, $full_name, $bio);
                }
                // if($cart->course->id != 52){
                //     $cart = Cart::find($cart->id);
                //     Mail::to(trim($cart->userId->email))
                //     ->send(new MoodleLms($cart, $user_id__found));
                //     Cart::where('id', $cart->id)->update(['lms_sent_at'=>now()]);
                // }
            }
            // ========================= End Simulation ==================================

            // Mail::to($user->email)->send(new SelfLms($user, $payment));

            if(is_null($send_email)){
                Mail::to(trim($cart->userId->email))
                // ->cc(['dabukarsh@bakkah.net.sa', 'malashqar@bakkah.net.sa', 'yreyala@bakkah.net.sa'])
                ->send(new SelfLms($cart, $user_id__found));
            }
            Cart::where('id', $cart->id)->update(['lms_sent_at'=>now()]);

        }else{
            Mail::to('aalhorany@bakkah.net.sa')
                ->cc(['hsalah@bakkah.net.sa', 'malqumbuz@bakkah.net.sa'])
                ->send(new FailSelfLms($cart));
        }
//        https://lms.bakkah.net.sa/index
//        hsalah@bakkah.net.sa
//        hani@123456
    }

    private function split_name($name) {
        $name = trim($name);
        $last_name = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
        $first_name = trim( preg_replace('#'.$last_name.'#', '', $name ) );
        return array($first_name, $last_name);
    }

    private function create_talentlms_account($cart, $full_name, $cert_no, $bio){

        $curl = curl_init();

        $email = $cart->userId->email;
        $e_portal_username = $cart->userId->username_lms;
        $e_portal_password = $cart->userId->password_lms;

        if(is_null($e_portal_username)){
            $UserHelper = new UserHelper();
            $user_lms = $UserHelper->GenerateUserLMS($email);;
            $e_portal_username = $user_lms['username'];
            $e_portal_password = $user_lms['password'];

            User::where('id', $cart->userId->id)->update([
                'username_lms'=>$user_lms['username'],
                'password_lms'=>$user_lms['password'],
            ]);
        }
        $lms_course_id = $cart->trainingOption->lms_course_id;

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://bakkah2.talentlms.com/api/v1/users/email:".$email,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Basic Sk0yemhENkVYYWplQmRoTVBHeWNuSkg0VHllWFh0Og=="
            ),
        ));
        $response = curl_exec($curl);

        curl_close($curl);
//        dump($curl);
        $user_id__found = -1;
        $responseData = json_decode($response, true);
        if(!isset($responseData['id'])){
            $curl = curl_init();
            $full_name1 = '.';
            if(isset($full_name[1]))
                $full_name1 = $full_name[1];

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://bakkah2.talentlms.com/api/v1/usersignup",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => array('first_name' => $full_name[0],'last_name' => $full_name1,'email' => $email,'login' => $e_portal_username,'password' => $e_portal_password,'status' => 'active','user_type' => 'Trainee-Type'),
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Basic Sk0yemhENkVYYWplQmRoTVBHeWNuSkg0VHllWFh0Og=="
                ),
            ));
            $response = curl_exec($curl);

            curl_close($curl);
            $responseData = json_decode($response, true);
        }
        else {
            $user_id__found = $responseData['id'];
        }

        if(!isset($responseData['id'])){
            return null;
        }
//        dd($responseData);
        // echo ' <br><br><br> New learner created successfully with id = '.$responseData['id'];

        $deactivation_date = Carbon::now()->add(365, 'day');
        // echo '<br><br><br>deactivation_date = '.$deactivation_date;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://bakkah2.talentlms.com/api/v1/edituser",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => array('user_id' => $responseData['id'],'deactivation_date' => $deactivation_date->isoFormat('DD/MM/YYYY')),
            CURLOPT_HTTPHEADER => array(
                "Authorization: Basic Sk0yemhENkVYYWplQmRoTVBHeWNuSkg0VHllWFh0Og=="
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        // echo ' <br><br><br>The Learner activated for one year '.$response;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://bakkah2.talentlms.com/api/v1/edituser",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => array('user_id' => $responseData['id'],'custom_field_1' => $cert_no, 'bio' => $bio),
            CURLOPT_HTTPHEADER => array(
                "Authorization: Basic Sk0yemhENkVYYWplQmRoTVBHeWNuSkg0VHllWFh0Og=="
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        // echo $response;
        $curl_course = curl_init();

        curl_setopt_array($curl_course, array(
            CURLOPT_URL => "https://bakkah2.talentlms.com/api/v1/addusertocourse",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => array('user_id' => $responseData['id'],'course_id' => $lms_course_id,'role' => 'learner'),
            CURLOPT_HTTPHEADER => array(
                "Authorization: Basic Sk0yemhENkVYYWplQmRoTVBHeWNuSkg0VHllWFh0Og=="
            ),
        ));
        $response_course = curl_exec($curl_course);
        curl_close($curl_course);
        // echo '<br><br><br> The learner successfully enrolled in the course and this is its result: '.$response_course;
        $responseData_course = json_decode($response_course, true);

        $return_val = false;
        if( (isset($responseData_course[0]['user_id']) && $responseData_course[0]['user_id'] > 0 ) && (isset($responseData_course[0]['course_id']) && $responseData_course[0]['course_id'] > 0 )){
            $return_val = true;
        }
        return [
            'return_val'=>$return_val,
            'user_id__found'=>$user_id__found,
        ];
      //        return $return_val;
    } // function create_talentlms_account

    public function LMS_online($cart, $send_email=null){

        $name = $cart->userId->en_name??null;
        if(is_null($name)){
            $arr = explode("@", $cart->userId->email, 2);
            $first = $arr[0];
            $name = $first . ' ' . $cart->userId->id;
        }
        $full_name = $this->split_name($name);

        $course_title = $cart->course->en_title??null;
        $bio = 'The LMS account was created automatically from Bakkah website for '.$name.' and enrolled in '.$course_title;

        // $lms_course_id = 103;//PMP Question Bank Simulators
        $moodle_course_id = $cart->trainingOption->lms_course_id??0;
        $moodle = $this->create_moodle_account($cart, $moodle_course_id, $full_name, $bio);
        if($moodle){
            $user_id__found = $moodle['user_id__found']??-1;
            $e_portal_username = $moodle['e_portal_username']??null;
            if($moodle['is_created_in_moodle']==true) {

                if($cart->exam_simulation_price > 0){
                // ========================= ٍStart Simulation ==================================
                    $training_option_id = $cart->trainingOption->exam_simulation_id;
                    $training_option = TrainingOption::find($training_option_id);

                    $moodle_lms_id = $training_option->lms_id??0;
                    $moodle_exam_sim_id = $training_option->lms_course_id??0;

                    if($moodle_lms_id==342 && $moodle_exam_sim_id > 0){
                        $moodle = $this->create_moodle_account($cart, $moodle_exam_sim_id, $full_name, $bio);
                    }
                // ========================= End Simulation ==================================
                }
            }

            // if($cart->course->id != 52){
                $cart = Cart::find($cart->id);

                if(is_null($send_email)){
                    Mail::to(trim($cart->userId->email))
                    // ->cc(['dabukarsh@bakkah.net.sa', 'malashqar@bakkah.net.sa', 'yreyala@bakkah.net.sa'])
                    ->send(new MoodleLms($cart, $user_id__found));
                }
                Cart::where('id', $cart->id)->update(['lms_sent_at'=>now()]);

                // if(auth()->check()){
                //     if(auth()->user()->id==2){
                //         dd('sucess '.$cart->id);
                //     }
                // }

            // }
        }else{
            $is_created_in_moodle = $moodle['is_created_in_moodle'];
            $moodle_error_msg     = $moodle['moodle_error_msg'];
            Mail::to('aalhorany@bakkah.net.sa')
                ->cc(['hsalah@bakkah.net.sa', 'malqumbuz@bakkah.net.sa'])
                ->send(new FailOnlineLms($cart));
        }
    }

    private function GetValue($mydb_moodle, $sql){
        $course_id = null;
        $result = $mydb_moodle->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $course_id = $row["id"];
            }
        } else {
            // echo "0 results";
        }
        return $course_id;
    }

    private function create_moodle_account($cart, $moodle_course_id, $full_name, $bio ){
        // Moodle DB table
        $moodle_servername = 'localhost';
        // $moodle_servername = '82.192.80.230';
        $moodle_dbname = 'learn_a';
        $moodle_username = 'learn_a';
        $moodle_password = 'v3xGoaza5a2n';

        $email = $cart->userId->email;
        $e_portal_username = $cart->userId->username_lms;
        $e_portal_password = $cart->userId->password_lms;

        if(is_null($e_portal_username)){
            $UserHelper = new UserHelper();
            $user_lms = $UserHelper->GenerateUserLMS($email);
            $e_portal_username = $user_lms['username'];
            $e_portal_password = $user_lms['password'];

            User::where('id', $cart->userId->id)->update([
                'username_lms'=>$user_lms['username'],
                'password_lms'=>$user_lms['password'],
            ]);
        }
        // $moodle_course_id = $cart->trainingOption->lms_course_id;
        $password_in_moodle = password_hash($e_portal_password, PASSWORD_DEFAULT); ///IMPORTANT!
        // $e_portal_username = 'hsalah123';
        // $e_portal_password = 'hsalah@123';
        // $full_name ='Hani M. Salah';
        // $name_split = explode(' ', $full_name);
        // $fname = $name_split[0];
        // $lname = $name_split[count($name_split)-1];
        $bio = htmlentities($bio, ENT_QUOTES, "UTF-8");
        $fname = htmlentities($full_name[0], ENT_QUOTES, "UTF-8");
        $lname = '.';
        if(isset($full_name[1]))
            $lname = htmlentities($full_name[1], ENT_QUOTES, "UTF-8");

        // echo '1- '.$fname.'<br>';
        // echo '2- '.$lname.'<br>';
        // exit();
        // $fname = 'Hani';
        // $lname = 'Salah Test from WP';
        // $email = 'hsalah@bakkah.net.sa';
        // $email = 'hanisalah78@yahoo.com';
        // $moodle_course_id = 84; //P3O速 Online in Moodle
        $duration = 365;

        $moodle_error = false;
        $moodle_error_msg = '';

        $is_created_in_moodle = false;

        $mydb_moodle = mysqli_connect($moodle_servername,$moodle_username,$moodle_password,$moodle_dbname);
        // $mydb_moodle = mysqli_connect($moodle_servername,$moodle_username,$moodle_password,$moodle_dbname) or die('Not connected : to the Moodle Database ' . mysqli_connect_error());

        if ($mydb_moodle -> connect_errno) {
            echo "Failed to connect to MySQL: " . $mydb_moodle -> connect_error;
            exit();
        }


        $user_id__found = -1;
        // $mydb_moodle = new wpdb($moodle_username,$moodle_password,$moodle_dbname,$moodle_servername);
        //if ($mydb_moodle->has_connected) {  // check DB connection
        if (!$mydb_moodle -> connect_errno) {

            $course_id = $this->GetValue($mydb_moodle, "SELECT id FROM course WHERE id=$moodle_course_id");
            if(empty($course_id) || intval($course_id)<=0 ){
                $moodle_error = true;
                $moodle_error_msg .= '<br>Course not exist in Moodle DB.';
            } // $course_id = $mydb_moodle->get_var

            $enrol_id = $this->GetValue($mydb_moodle, "SELECT id FROM enrol WHERE courseid=$moodle_course_id AND enrol='manual'");
            if(empty($enrol_id) || intval($enrol_id)<=0 ){
                $moodle_error = true;
                $moodle_error_msg .= '<br>Enrol Not Allowed in Moodle.';
            } // $enrol_id = $mydb_moodle->get_var

            $context_id = $this->GetValue($mydb_moodle, "SELECT id FROM context WHERE contextlevel=50 AND instanceid=$moodle_course_id");
            if(empty($context_id) || intval($context_id)<=0 ){
                $moodle_error = true;
                $moodle_error_msg .= '<br>Context Enrol Not Allowed in Moodle.';
            } // $enrol_id = $mydb_moodle->get_var


            if(!$moodle_error){ // All thing are good and no errors in moodle variables

                ///We were just getting variables from moodle. Here is were the enrolment begins:
                $user_id = $this->GetValue($mydb_moodle, "SELECT id FROM user WHERE email='$email'");
                $user_id__found = $user_id;

                if(empty($user_id) || intval($user_id)<=0 ){   // user not exist by email

                    $sql = "INSERT INTO user (auth, confirmed, mnethostid, username, password, firstname, lastname, email, city, country, lang, calendartype, description)
                    VALUES ('manual', 1, 1, '".$e_portal_username."', '".$password_in_moodle."', '".$fname."', '".$lname."', '".$email."', 'Riyadh', 'SA', 'en', 'gregorian', '".$bio."')";
                    $insert_user_2_moodle = $mydb_moodle->query($sql);
                    if ($insert_user_2_moodle) {
                        $user_id = $mydb_moodle->insert_id;
                        // echo 'New user account added to Moodle successfully and his/her id is: '.$user_id;
                    }else{

                        $user_id = $this->GetValue($mydb_moodle, "SELECT id FROM user WHERE username='$e_portal_username'");

                        if(!empty($user_id) || intval($user_id)>0 ){   //3641

                            // $e_portal_username = $e_portal_username.'#'.strtolower($this->random_str(3));

                            $sql = "INSERT INTO user (auth, confirmed, mnethostid, username, password, firstname, lastname, email, city, country, lang, calendartype, description)
                            VALUES ('manual', 1, 1, '".$e_portal_username."', '".$password_in_moodle."', '".$fname."', '".$lname."', '".$email."', 'Riyadh', 'SA', 'en', 'gregorian', '".$bio."')";
                            $insert_user_2_moodle_3 = $mydb_moodle->query($sql);
                            if ($insert_user_2_moodle_3) {
                                $user_id = $mydb_moodle->insert_id;
                                // echo 'New user account added to Moodle successfully and his/her id is: '.$user_id;
                            }else{
                                $moodle_error = true;
                                $moodle_error_msg .= '<br>Error occure when create account in Moodle.';
                                $user_id = 0;
                            } // if ($insert_user_2_moodle_3) {

                        }  // if(!empty($user_id) || intval($user_id)>0 ){


                    } // else{

                } // $user_id = $mydb_moodle->get_var


                if(!empty($user_id) || intval($user_id)>0 ){
                    $time = time();
                    $ntime = $time + 60*60*24*$duration; //How long will it last enroled $duration = days, this can be 0 for unlimited.

                    // First
                    $sql = "INSERT INTO user_enrolments (status, enrolid, userid, timestart, timeend, timecreated, timemodified)
                            VALUES (0, '".$enrol_id."', '".$user_id."', '".$time."', '".$ntime."', '".$time."', '".$time."')";
                    $insert_2_user_enrolments = $mydb_moodle->query($sql);
                    if ($insert_2_user_enrolments) {
                        $user_enrolments = $mydb_moodle->insert_id;
                    }else{
                        $moodle_error = true;
                        $moodle_error_msg .= '<br>User Exist in Moodle with id:'.$user_id.', but Error occure when add him into user_enrolments in Moodle.';
                        $user_enrolments = 0;
                    }

                    // Second
                    $sql = "INSERT INTO role_assignments (roleid, contextid, userid, timemodified)
                            VALUES (5, '".$context_id."', '".$user_id."', '".$time."')";
                    $insert_2_role_assignments = $mydb_moodle->query($sql);
                    if ($insert_2_role_assignments) {
                        $role_assignments = $mydb_moodle->insert_id;
                    }else{
                        $moodle_error = true;
                        $moodle_error_msg .= '<br>User Exist into Moodle, but Error occure when add him into role_assignments in Moodle.';
                        $role_assignments = 0;
                    }

                    // Final result
                    if( (!empty($user_id) || intval($user_id)>0) && (!empty($user_enrolments) || intval($user_enrolments)>0) && (!empty($role_assignments) || intval($role_assignments)>0) ){
                        // Send email to training that result is successfully
                        // echo 'Every thing is done successfully!';
                        $is_created_in_moodle = true;

                    }
                } // if(!empty($user_id) || intval($user_id)>0 ){
            } // if(!$moodle_error)
            $mydb_moodle->close();
        }else{
            $moodle_error = true;
            $moodle_error_msg .= '<br>Could not connect to Moodle DB.';
        }

        return array(
            'is_created_in_moodle' => $is_created_in_moodle,
            'moodle_error_msg'     => $moodle_error_msg,
            'e_portal_username'    => $e_portal_username,
            'user_id__found'    => $user_id__found,
        );

    } // End of function create_moodle_account

    public function self(){
        return view(FRONT.'.education.lms.self');
    }

    public function online(){
        return view(FRONT.'.education.lms.online');
    }

}
