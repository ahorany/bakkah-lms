<?php
namespace App\Helpers\Models;

use App\Events\MailChimpEvent;
use App\User;
use Exception;
use Illuminate\Support\Facades\Cookie;

class UserHelper {

    public function random_str(
        $length,
        $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!#$%^*_'
    ) {
        $str = '';
        $max = mb_strlen($keyspace, '8bit') - 1;
        if ($max < 1) {
            throw new Exception('$keyspace must be at least two characters long');
        }
        for ($i = 0; $i < $length; ++$i) {
            $str .= $keyspace[random_int(0, $max)];
        }
        return $str;
    }

    public function GenerateUserLMS($email)
    {
        $email_array      =  explode('@', $email);
        $username_from_email  =  str_replace(".","",$email_array['0']);
        $e_portal_username    = strtolower($username_from_email);
        $e_portal_password    = $e_portal_username.'@'.$this->random_str(3);
        return [
            'username'=>$e_portal_username,
            'password'=>$e_portal_password,
        ];
    }

    public function UpdateOrCreate($validated)
    {
        $user = User::where('email', $validated['email'])->first();
        $user_lms = $this->GenerateUserLMS($validated['email']);

        $data = json_encode([
            'en'=>$validated['en_name'],
            'ar'=>$validated['en_name']
        ], JSON_UNESCAPED_UNICODE);

        $args = [
            'name'=>$data,
//            'name'=>$validated['en_name'],
            'job_title'=>$validated['job_title'],
            'company'=>$validated['company'],
            'country_id'=>$validated['country_id'],
            'mail_subscribe'=>$validated['mail_subscribe']??0,
            'mobile'=>$validated['mobile'],
            'gender_id'=>$validated['gender_id'],
            'locale'=>app()->getLocale(),
        ];

        // dump('Course Registration');
        // dump($user);
        event(new MailChimpEvent($user, "Course Registration"));
        // dd('Course Registration');
        if(is_null($user)){

            // dump('test');
            $user = User::create(array_merge($args, [
                'email'=>$validated['email'],
                'username_lms'=>$user_lms['username'],
                'password_lms'=>$user_lms['password'],
            ]));
            // $Mailchimp = new Mailchimp;
            // $Mailchimp->sync($user, null, "Course Registration");
            $send_email = true;
            if(auth()->check()){
                if(auth()->user()->user_type==315){
                    $send_email = false;
                }
            }
            if($send_email){
                event(new MailChimpEvent($user, "Course Registration"));
            }
        }
        else{

            if(is_null($user->username_lms) || is_null($user->password_lms)){
                $args = array_merge($args, [
                    'username_lms'=>$user_lms['username'],
                    'password_lms'=>$user_lms['password'],
                ]);
            }
            User::where('id', $user->id)
                ->update($args);
        }

        return $user;
    }

    public function user_token(){

        if (Cookie::get('user_token') != null) {
            $user_token = Cookie::get('user_token');
        }
        else{
            $user_token = rand(000000, 999999) . time();

            Cookie::queue('user_token', $user_token, (60 * 24 * 30 * 12));
            // Cookie::make('user_token', $user_token, (60*365));
        }
        return $user_token;
    }
}
