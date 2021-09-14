<?php

namespace App\Http\Controllers\Testing;

use App\Constant;
use App\Http\Controllers\Controller;
use App\Models\Training\Cart;
use App\Models\Training\CartFeature;
use App\Models\Training\CartMaster;
use App\Models\Training\Course;
use App\Models\Training\Payment;
use App\Models\Training\Session;
use App\Models\Training\TrainingOption;
use App\Models\Training\TrainingOptionFeature;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Modules\CRM\Entities\B2bMaster;
use Modules\CRM\Entities\GroupInvoiceMaster;
use Modules\CRM\Entities\Organization;

class DataMigrationController extends Controller
{
    public function laravel(){

        /******************************************************/
        // $this->LaravelB2b();
        // dd('LaravelB2b');
        // update cart_masters set total_after_vat = total + vat_value where id <=10;
        /******************************************************/
        /**
         SELECT * FROM `cart_masters`;
         SELECT * FROM `group_invoice_masters`;
         select master_id, count(id)
        from payments
        where `code` is null
        group by master_id
        having count(id)!=1
         */
        // dd('Stop Migration');
        /******************************************************/
        //#1
        // $this->FixTrainingOptionFeature();
        // dd('FixTrainingOptionFeature');
        /******************************************************/
        /**
         * DQT
         SELECT * FROM `training_option_features`;
         */
        ini_set('max_execution_time', 0);

        /******************************************************/
        //#2
        // $this->GenerateCartMastersNULL_laravel();
        // dd('GenerateCartMastersNULL_laravel');
        /******************************************************/
        /**
         select master_id, count(id) from payments  group by master_id having count(id)!=1
         */
        /******************************************************/
        // $this->FixCartFeature();
        // dd('FixCartFeature');
        /******************************************************/
        /**
         * DQT
         SELECT * FROM `cart_features`;
         */
        dd('Laravel Done');
    }

    private function conn(){
        return DB::connection('mysql2');
    }

    //http://127.0.0.1:8000/data_migration/wp
    public function wp(){

        ini_set('max_execution_time', 0);
        /******************************************************/
        //#1
        // $this->migrate_trainers();
        // dd('migrate_trainers');
        /******************************************************/
        /** Data Quality Test */
        /**
            select *
            from bakkah_learning_wpbak_db.bak_posts bp
            inner join bakkah_learning_wpbak_db.bak_postmeta bm on bm.post_id = bp.id
            and bm.meta_key = 'email'
            where bp.post_type='trainers'
            and bm.meta_value in(
                select email from bakkah_learning_cart_db.users
            )
        */

        //#1
        /******************************************************/
        // $this->AddCitiesToLaravel('laravel_sessions');
        // dd('AddCitiesToLaravel');
        /******************************************************/
        /**
         * DQT
         SELECT * FROM `constants` where post_type='cities';
         */

         //#2
         /******************************************************/
        // $this->AddCitiesToLaravel('laravel_sessions_b2b');
        // dd('AddCitiesToLaravel');
        /******************************************************/
        /**
         * DQT
         SELECT * FROM `constants` where post_type='cities';
         */
        // &&&&&&&&&&&&&&&&&&&&&&&&  B2C  &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
        $this->post_type = 10;
        $this->session_table = 'laravel_sessions';
        $this->reg_table = 'laravel_courses_reg';
        $this->B2cOrB2b = 'b2c';
        // Courses exists in laravel
        // A
        /******************************************************/
        // $this->migrate_online_self_laravel_id_non__zero();
        // dd('B2C: migrate_online_self_laravel_id_non__zero');
        /******************************************************/
        /** Data Quality Test */
        /**
            select count(id) from sessions where wp_id is not null and wp_city in('Online-b2c');
            select count(id) from cart_masters where wp_id is not null and wp_city in('Online-b2c');
            select count(id) from carts where wp_id is not null and wp_city in('Online-b2c');
            select count(id) from payments where wp_id is not null and wp_city in('Online-b2c');
            select count(id) from training_options where wp_id is not null and wp_city in('Online-b2c');

            delete from sessions where wp_id is not null and wp_city in('Online-b2c');
            delete from cart_masters where wp_id is not null and wp_city in('Online-b2c');
            delete from carts where wp_id is not null and wp_city in('Online-b2c');
            delete from payments where wp_id is not null and wp_city in('Online-b2c');
            delete from training_options where wp_id is not null and wp_city in('Online-b2c');
        */

        // B
        /******************************************************/
        // $this->migrate_class_room_laravel_id_non__zero();
        // dd('migrate_class_room_laravel_id_non__zero');
        /******************************************************/
        /** Data Quality Test */
        /**
            select count(id) from sessions where wp_id is not null and wp_city not in('Online-b2c');
            select count(id) from cart_masters where wp_id is not null and wp_city not in('Online-b2c');
            select count(id) from carts where wp_id is not null and wp_city not in('Online-b2c');
            select count(id) from payments where wp_id is not null and wp_city not in('Online-b2c');
            select count(id) from training_options where wp_id is not null and wp_city not in('Online-b2c');

            ---------------------------------------------------
            select invoice_number, count(id)
            from cart_masters
            where wp_id is not null and wp_city not in('Online-b2c')
            group by invoice_number
            having count(id) != 1;

            select invoice_number, count(id)
            from carts where wp_id is not null and wp_city not in('Online-b2c')
            group by invoice_number
            having count(id) != 1;

            select master_id, count(id)
            from payments where wp_id is not null and wp_city not in('Online-b2c')
            group by master_id
            having count(id) != 1;
            ---------------------------------------------------

            delete from sessions where wp_id is not null and wp_city not in('Online-b2c');
            delete from cart_masters where wp_id is not null and wp_city not in('Online-b2c');
            delete from carts where wp_id is not null and wp_city not in('Online-b2c');
            delete from payments where wp_id is not null and wp_city not in('Online-b2c');
            delete from training_options where wp_id is not null and wp_city not in('Online-b2c');
        */
        // //     ///////////////////////////////////////////////////

        // &&&&&&&&&&&&&&&&& Start: Don't Execute It &&&&&&&&&&&&&&&&&
        //#4
        // $this->migrate_online_self_laravel_id__zero();
        // dd('migrate_online_self_laravel_id__zero');
        // &&&&&&&&&&&&&&&&& End  : Don't Execute It &&&&&&&&&&&&&&&&&
        //#5
        // $this->migrate_class_room_laravel_id__zero();
        // dd('migrate_class_room_laravel_id__zero');
        /** Data Quality Test */
        /**
            select count(id) from courses where wp_id is not null and wp_city in('ClassRoom-New-b2c');
            select count(id) from sessions where wp_id is not null and wp_city in('ClassRoom-New-b2c');
            select count(id) from cart_masters where wp_id is not null and wp_city in('ClassRoom-New-b2c');
            select count(id) from carts where wp_id is not null and wp_city in('ClassRoom-New-b2c');
            select count(id) from payments where wp_id is not null and wp_city in('ClassRoom-New-b2c');
            select count(id) from training_options where wp_id is not null and wp_city in('ClassRoom-New-b2c');

            ---------------------------------------------------
            select invoice_number, count(id)
            from cart_masters
            where wp_id is not null and wp_city in('ClassRoom-New-b2c')
            group by invoice_number
            having count(id) != 1;

            select invoice_number, count(id)
            from carts where wp_id is not null and wp_city in('ClassRoom-New-b2c')
            group by invoice_number
            having count(id) != 1;

            select master_id, count(id)
            from payments where wp_id is not null and wp_city in('ClassRoom-New-b2c')
            group by master_id
            having count(id) != 1;
            ---------------------------------------------------

            delete from courses where wp_id is not null and wp_city in('ClassRoom-New-b2c');
            delete from sessions where wp_id is not null and wp_city in('ClassRoom-New-b2c');
            delete from cart_masters where wp_id is not null and wp_city in('ClassRoom-New-b2c');
            delete from carts where wp_id is not null and wp_city in('ClassRoom-New-b2c');
            delete from payments where wp_id is not null and wp_city in('ClassRoom-New-b2c');
            delete from training_options where wp_id is not null and wp_city in('ClassRoom-New-b2c');
        */
        // &&&&&&&&&&&&&&&&&&&&&&&&  B2C  &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&

        // &&&&&&&&&&&&&&&&&&&&&&&&  B2B  &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
        $this->post_type = 370;
        $this->session_table = 'laravel_sessions_b2b';
        $this->reg_table = 'bak_bakkah_b2b_candidates';
        $this->B2cOrB2b = 'b2b';
        //#2
        // &&&&&&&&&&&&&&&&&&&&&&&&  B2B  &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
        // $this->migrate_online_self_laravel_id_non__zero();
        // dd('B2B: migrate_online_self_laravel_id_non__zero');
        // &&&&&&&&&&&&&&&&&&&&&&&&  B2B  &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
        /** Data Quality Test */
        /**
            select count(id) from sessions where wp_id is not null and wp_city in('Online-b2b');
            select count(id) from cart_masters where wp_id is not null and wp_city in('Online-b2b');
            select count(id) from carts where wp_id is not null and wp_city in('Online-b2b');
            select count(id) from payments where wp_id is not null and wp_city in('Online-b2b');
            select count(id) from training_options where wp_id is not null and wp_city in('Online-b2b');

            delete from sessions where wp_id is not null and wp_city in('Online-b2b');
            delete from cart_masters where wp_id is not null and wp_city in('Online-b2b');
            delete from carts where wp_id is not null and wp_city in('Online-b2b');
            delete from payments where wp_id is not null and wp_city in('Online-b2b');
            delete from training_options where wp_id is not null and wp_city in('Online-b2b');
        */
        //#3
        // &&&&&&&&&&&&&&&&&&&&&&&&  B2B  &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
        // $this->migrate_class_room_laravel_id_non__zero();
        // dd('B2B: migrate_class_room_laravel_id_non__zero');
        // &&&&&&&&&&&&&&&&&&&&&&&&  B2B  &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
        /** Data Quality Test */
        /**
            select count(id) from sessions where wp_id is not null and wp_city not in('Online-b2b');
            select count(id) from cart_masters where wp_id is not null and wp_city not in('Online-b2b');
            select count(id) from carts where wp_id is not null and wp_city not in('Online-b2b');
            select count(id) from payments where wp_id is not null and wp_city not in('Online-b2b');
            select count(id) from training_options where wp_id is not null and wp_city not in('Online-b2b');

            ---------------------------------------------------
            select invoice_number, count(id)
            from cart_masters
            where wp_id is not null and wp_city not in('Online-b2b')
            group by invoice_number
            having count(id) != 1;

            select invoice_number, count(id)
            from carts where wp_id is not null and wp_city not in('Online-b2b')
            group by invoice_number
            having count(id) != 1;

            select master_id, count(id)
            from payments where wp_id is not null and wp_city not in('Online-b2b')
            group by master_id
            having count(id) != 1;
            ---------------------------------------------------

            delete from sessions where wp_id is not null and wp_city not in('Online-b2b');
            delete from cart_masters where wp_id is not null and wp_city not in('Online-b2b');
            delete from carts where wp_id is not null and wp_city not in('Online-b2b');
            delete from payments where wp_id is not null and wp_city not in('Online-b2b');
            delete from training_options where wp_id is not null and wp_city not in('Online-b2b');
        */

        // //     ///////////////////////////////////////////////////
        // $this->migrate_online_self_laravel_id__zero();//#4
        // dd('B2B: migrate_online_self_laravel_id__zero');

        // $this->migrate_class_room_laravel_id__zero();//#5
        // dd('B2B: migrate_class_room_laravel_id__zero');
        // &&&&&&&&&&&&&&&&&&&&&&&&  B2B  &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
        dd('WP Done');
    }

    //#2
    // Courses exists in laravel
    private function migrate_online_self_laravel_id_non__zero()
    {
        $courses = $this->__laravel_courses()
        ->where('laravel_id', '!=', 0)
        ->get();
        // dd($courses);
        $i=0;
        foreach($courses as $course)
        {
            $i++;
            $this->migrate_online_self__sessions($course, $course->laravel_id, 'Online-'.$this->B2cOrB2b);
        }
        dump('i:'.$i);
    }

    //#3
    //First Step: courses exists
    private function migrate_class_room_laravel_id_non__zero()
    {
        $courses = $this->__laravel_courses()
        ->where('laravel_id', '!=', 0)
        ->get();
        $i = 0;
        foreach($courses as $course)
        {
            $i++;
            $this->migrate_class_room__sessions($course, $course->laravel_id, 'ClassRoom-'.$this->B2cOrB2b);
        }
        dump('Class Room-i:'.$i);
    }

    private function migrate_online_self_laravel_id__zero()
    {
        $courses = $this->__laravel_courses()
        ->where('laravel_id', 0)
        ->get();
        $i = 0;
        foreach($courses as $course)
        {
            $i++;
            $course_laravel = $this->__create_course($course, 'Online-New-'.$this->B2cOrB2b);
            $this->migrate_online_self__sessions($course, $course_laravel->id, 'Online-New-'.$this->B2cOrB2b);
        }
        dump('Online-New-i:'.$i);
    }

    //#5
    private function migrate_class_room_laravel_id__zero()
    {
        $courses = $this->__laravel_courses()
        ->where('laravel_id', 0)
        ->get();
        $i = 0;
        foreach($courses as $course)
        {
            $i++;
            $course_laravel = $this->__create_course($course, 'ClassRoom-New-'.$this->B2cOrB2b);
            $this->migrate_class_room__sessions($course, $course_laravel->id, 'ClassRoom-New-'.$this->B2cOrB2b);
        }
        dump('ClassRoom-New-i:'.$i);
    }

    private function migrate_online_self__sessions($course, $laravel_id, $wp_city)
    {
        $sessions = $this->conn()
        ->table($this->session_table)
        ->where('course_id', $course->id)//by wp_id
        ->whereIn('city', ['Online', 'Self'])
        ->select('*')
        ->get();
        foreach($sessions as $session)
        {
            $constant_id = [
                'Online'=>13,
                'Self'=>11,
            ][$session->city]??13;

            $trainingOption_laravel = $this->__FirstOrCreateTrainingOption($laravel_id, $constant_id, $wp_city);
            ///////////////////BODY///////////////////
            if($this->post_type==10){
                $this->__migrate_BODY__zero($course, $session, $trainingOption_laravel, -1, $laravel_id, $wp_city);
            }
            else{
                $this->__migrate_BODY__zero__b2b($course, $session, $trainingOption_laravel, -1, $laravel_id, $wp_city);
            }
        }
    }

    private function migrate_class_room__sessions($course, $laravel_id, $wp_city)
    {
        $sessions = $this->conn()
        ->table($this->session_table)
        ->where('course_id', $course->id)//by wp_id
        ->whereNotIn('city', ['Online', 'Self'])
        // ->where('attendance_count', 0)//wp_migrate
        ->select('*')
        ->get();
        foreach($sessions as $session)
        {
            $constant_id = 383;//Class Room

            $trainingOption_laravel = $this->__FirstOrCreateTrainingOption($laravel_id, $constant_id, $wp_city);
            $constant = Constant::where('slug', $session->city)->first();
            ///////////////////BODY///////////////////
            if($this->post_type==10){
                $this->__migrate_BODY__zero($course, $session, $trainingOption_laravel, $constant->id??-1, $laravel_id, $wp_city);
            }
            else{
                $this->__migrate_BODY__zero__b2b($course, $session, $trainingOption_laravel, $constant->id??-1, $laravel_id, $wp_city);
            }
        }
    }

    private function __migrate_BODY__zero($course, $session, $trainingOption_laravel, $city_id=-1, $laravel_id, $wp_city)
    {
        $post_type = [
            10=>-1,//374//B2C
            370=>370,//B2B
        ][$course->post_type]??-5;//To review it when finished

        $session_laravel = Session::firstOrCreate([
            'training_option_id'=>$trainingOption_laravel->id,
            'date_from'=>$session->date_from,
            'date_to'=>$session->date_to,
            'wp_id'=>$session->id,
            'wp_city'=>$wp_city,
            'post_year'=>date('Y', strtotime($session->date_from)),
            'type_id'=>$post_type,
        ], [
            'duration'=>$session->duration,
            'duration_type'=>$session->duration_type,
            'session_time'=>$session->session_time,
            'price'=>$session->price,
            'exam_price'=>$session->exam_price,
            'vat'=>$session->vat,
            'total'=>$session->total,
            'session_start_time'=>$session->session_start_time,
            'show_in_website'=>0,
            'lang_id'=>$session->lang_id,
            'trainer_id'=>$session->laravel_trainer_id,
            'city_id'=>$city_id,
            'wp_migrate'=>1,
        ]);

        $courses_regs = $this->conn()
        ->table($this->reg_table)//B2C
        ->where('course_id', $course->id)
        ->where('session_id', $session->id)
        ->where('category', 'Course')
        ->select('*')
        ->get();
        foreach($courses_regs as $courses_reg)
        {
            ///////////Start User/////////////////
            $gender_id = [
                'male'=>43,
                'female'=>44,
            ][$courses_reg->gender]??43;
            $user_laravel = User::firstOrCreate([
                'email'=>$courses_reg->email,
            ],[
                'name'=>$courses_reg->name,
                'gender_id'=>$gender_id,
                'user_type'=>41,
                'company'=>$courses_reg->company??null,
                'job_title'=>$courses_reg->job_title,
                'country_id'=>58,
                'wp_migrate'=>1,
            ]);
            ///////////End User/////////////////

            $status_id = [
                68=>357,
                317=>358,
                316=>359,
                315=>357,
                63=>355,
                332=>357,
            ][$courses_reg->payment_status]??355;

            $post_type = [
                10=>374,//B2C
                370=>370,//B2B
            ][$course->post_type]??-5;//To review it when finished

            //Just Create
            $cartMaster_laravel = CartMaster::firstOrCreate([
                'type_id'=>$post_type,
                'status_id'=>$status_id,
                'user_id'=>$user_laravel->id,
                'payment_status'=>$courses_reg->payment_status,
                'total'=>$courses_reg->total,
                'vat'=>$courses_reg->vat,
                'vat_value'=>$courses_reg->vat_value,
                // 'total_after_vat'=>$courses_reg->invoice_amount,
                'total_after_vat'=>$courses_reg->total_after_vat,
                'invoice_number'=>$courses_reg->invoice_no,
                'registered_at'=>$courses_reg->date_time,
                'wp_id'=>$courses_reg->ref_id,
                'wp_city'=>$wp_city,
                'post_year'=>date('Y', strtotime($courses_reg->date_time)),
            ],[
                'wp_migrate'=>1,
            ]);

            //Just Create
            $discount = 0;
            $discount_value = 0;
            if(!is_null($courses_reg->discount) && !empty($courses_reg->discount)){
                $discount = $courses_reg->discount;
                $discount_value = $courses_reg->discount_value;
            }
            $cart_laravel = Cart::firstOrCreate([
                'master_id'=>$cartMaster_laravel->id,
                'session_id'=>$session_laravel->id,
                'training_option_id'=>$trainingOption_laravel->id,
                'course_id'=>$laravel_id,
                'user_id'=>$user_laravel->id,
                'payment_status'=>$courses_reg->payment_status,
                'price'=>$courses_reg->price,
                'exam_price'=>$courses_reg->exam_price,
                'take2_price'=>$courses_reg->take2_price,
                'discount'=>$discount,
                'discount_value'=>$discount_value,
                'total'=>$courses_reg->total,
                'vat'=>$courses_reg->vat,
                'vat_value'=>$courses_reg->vat_value,
                // 'total_after_vat'=>$courses_reg->invoice_amount,
                'total_after_vat'=>$courses_reg->total_after_vat,
                'registered_at'=>$courses_reg->date_time,
                'wp_id'=>$courses_reg->ref_id,
                'wp_city'=>$wp_city,
                'post_year'=>date('Y', strtotime($courses_reg->date_time)),
            ],[
                'wp_migrate'=>1,
            ]);

            if($courses_reg->payment_status!=332)
            {
                //Just Create
                Payment::firstOrCreate([
                    'master_id'=>$cartMaster_laravel->id,
                    'wp_id'=>$courses_reg->ref_id,
                    'wp_city'=>$wp_city,
                    'post_year'=>date('Y', strtotime($courses_reg->date_time)),
                ],[
                    'paid_in'=>$courses_reg->total_after_vat,
                    // 'paid_in'=>$courses_reg->invoice_amount,
                    'payment_status'=>$courses_reg->payment_status,
                    'paid_at'=>$courses_reg->payment_date,
                    'user_id'=>$user_laravel->id,
                    'coin_id'=>334,
                    'wp_migrate'=>1,
                ]);
            }
        }
    }

    private function __migrate_BODY__zero__b2b($course, $session, $trainingOption_laravel, $city_id=-1, $laravel_id, $wp_city)
    {
        $post_type = [
            10=>-1,//374//B2C
            370=>370,//B2B
        ][$course->post_type]??-5;//To review it when finished

        $session_laravel = Session::firstOrCreate([
            'training_option_id'=>$trainingOption_laravel->id,
            'date_from'=>$session->date_from,
            'date_to'=>$session->date_to,
            'wp_id'=>$session->id,
            'wp_city'=>$wp_city,
            'post_year'=>date('Y', strtotime($session->date_from)),
            'type_id'=>$post_type,
        ], [
            'duration'=>$session->duration,
            'duration_type'=>$session->duration_type,
            'session_time'=>$session->session_time,
            'price'=>$session->price,
            'exam_price'=>$session->exam_price,
            'vat'=>$session->vat,
            'total'=>$session->total,
            'session_start_time'=>$session->session_start_time,
            'show_in_website'=>0,
            'lang_id'=>$session->lang_id,
            'trainer_id'=>$session->laravel_trainer_id,
            'city_id'=>$city_id,
            'wp_migrate'=>1,
        ]);

        $courses_regs = $this->conn()
        ->table($this->reg_table)//B2C
        ->where('course_id', $course->id)
        ->where('session_id', $session->id);

        $_count = $courses_regs->count();
        $_count = $_count!=0?$_count:1;

        $courses_regs = $courses_regs->select('*')->get();

        $organization = Organization::find($session->client_id);

        $gender_id = 43;
        $org_user_laravel = User::firstOrCreate([
            'email'=>$organization->email,
        ],[
            'name'=>$organization->name,
            'gender_id'=>$gender_id,
            'user_type'=>41,
            'company'=>$organization->title??null,
            'job_title'=>$organization->job_title,
            'country_id'=>58,
            'wp_migrate'=>1,
        ]);

        $total = $session->total??0;

        $vat_value_master = round($total * (($session->vat)/100),2);

        $post_type = [
            10=>374,//B2C
            370=>370,//B2B
        ][$course->post_type]??-5;//To review it when finished

        $cartMaster_laravel = CartMaster::firstOrCreate([
            'type_id'=>$post_type,
            'status_id'=>-1,
            'user_id'=>$org_user_laravel->id,
            'payment_status'=>$session->payment_status,
            'total'=>$session->price,
            'vat'=>$session->vat,
            'vat_value'=>$vat_value_master,
            'total_after_vat'=>$total??0,
            'invoice_number'=>$session->invoice_no,
            'registered_at'=>$session->date_time,
            'wp_id'=>$session->id,
            'wp_city'=>$wp_city,
            'post_year'=>date('Y', strtotime($session->date_time)),
        ],[
            'wp_migrate'=>1,
        ]);

        $price = round($total/$_count, 2);
        $vat_value = round($price*($session->vat/100),2);
        $invoice_amount = round(($price + $vat_value),2);

        $groupInvoiceMaster = GroupInvoiceMaster::firstOrCreate([
            'master_id'=>$cartMaster_laravel->id,
            'course_id'=>$laravel_id,
            'session_id'=>$session_laravel->id,
            'organization_id'=>$organization->id,
            'price'=>$price,
            'total'=>$price,
            'vat'=>$session->vat,
            'vat_value'=>$vat_value,
            'total_after_vat'=>$invoice_amount,
            'created_by'=>1,
            'updated_by'=>1,
            'wp_id'=>$session->id,
            'wp_city'=>$wp_city,
            'post_year'=>date('Y', strtotime($session->date_time)),
        ]);

        foreach($courses_regs as $courses_reg)
        {
            ///////////Start User/////////////////
            $gender_id = [
                'Male'=>43,
                'Female'=>44,
            ][$courses_reg->gender]??43;
            $user_laravel = User::firstOrCreate([
                'email'=>$courses_reg->email,
            ],[
                'name'=>$courses_reg->name,
                'gender_id'=>$gender_id,
                'user_type'=>41,
                'company'=>json_decode($organization->title)->en??null,
                'job_title'=>$courses_reg->job_title,
                'country_id'=>58,
                'wp_migrate'=>1,
            ]);
            ///////////End User/////////////////

            $status_id = [
                68=>357,
                317=>358,
                316=>359,
                315=>357,
                63=>355,
                332=>357,
            ][$session->payment_status]??355;

            //Just Create

            $cart_laravel = Cart::firstOrCreate([
                'master_id'=>$cartMaster_laravel->id,
                'session_id'=>$session_laravel->id,
                'training_option_id'=>$trainingOption_laravel->id,
                'course_id'=>$laravel_id,
                'user_id'=>$user_laravel->id,
                'payment_status'=>$session->payment_status,
                'price'=>$price,
                'exam_price'=>0,
                'take2_price'=>0,
                'discount'=>0,
                'discount_value'=>0,
                'total'=>$price,
                'vat'=>$session->vat,
                'vat_value'=>$vat_value,
                'total_after_vat'=>$invoice_amount,
                'registered_at'=>$courses_reg->date_time,
                'wp_id'=>$courses_reg->id,
                'wp_city'=>$wp_city,
                'post_year'=>date('Y', strtotime($courses_reg->date_time)),
            ],[
                'wp_migrate'=>1,
            ]);
        }

        if($session->payment_status!=332)
        {
            //Just Create
            Payment::firstOrCreate([
                'master_id'=>$cartMaster_laravel->id,
                'wp_id'=>$session->id,
                'wp_city'=>$wp_city,
                'post_year'=>date('Y', strtotime($session->date_time)),
            ],[
                'paid_in'=>$session->total,
                'payment_status'=>$session->payment_status,
                'paid_at'=>$session->payment_date,
                'user_id'=>$org_user_laravel->id,
                'coin_id'=>334,
                'wp_migrate'=>1,
            ]);
        }

       //  End of Hereeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee
    }

    //#1
    private function migrate_trainers()
    {
        $posts = $this->conn()
        ->table('bak_posts')
        ->where('post_type', 'trainers')
        ->select('*')
        ->get();
        $i=0;
        foreach($posts as $post)
        {
            $gender_id = [
                'Male'=>43,
                'Female'=>44,
            ][$this->GetTrainerInfo($post->ID, 'gender')]??43;

            $mobile = $this->GetTrainerInfo($post->ID, 'mobile');
            $email = $this->GetTrainerInfo($post->ID, 'email');
            /////////////////////////////////////
            if(!is_null($email) && !empty($email))
            {
                dump($email);
                $data = json_encode([
                    'en'=>$post->post_title,
                    'ar'=>$post->post_title,
                ], JSON_UNESCAPED_UNICODE);
                request()->en_name = $post->post_title;
                request()->ar_name = $post->post_title;

                $user = User::firstOrCreate([
                    'email'=>$email,
                ],[
                    'name'=>$data,
                    'gender_id'=>$gender_id,
                    'mobile'=>$mobile,
                    'user_type'=>326,//Trainer
                    'company'=>'Bakkah',
                    'job_title'=>'Bakkah Trainer',
                    'country_id'=>58,
                    'role_id'=>99,
                    'wp_migrate'=>1,
                ]);

                $this->conn()
                ->table('laravel_sessions')
                ->where('trainer_id', $post->ID)
                ->update([
                    'laravel_trainer_id'=>$user->id,
                ]);

                $this->conn()
                ->table('laravel_sessions_b2b')
                ->where('trainer_id', $post->ID)
                ->update([
                    'laravel_trainer_id'=>$user->id,
                ]);
                $i++;
            }
        }
        dump('i: '.$i);
    }

    private function GetTrainerInfo($post_id, $meta_key='gender')
    {
        $postmeta = $this->conn()
        ->table('bak_postmeta')
        ->where('post_id', $post_id)
        ->where('meta_key', $meta_key)
        ->select('meta_key', 'meta_value')
        ->first();

        return $postmeta->meta_value;
    }

    //#2
    private function AddCitiesToLaravel($session_table='laravel_sessions')
    {
        $sessions = $this->conn()
        ->table($session_table)
        ->whereNotIn('city', ['Online', 'Self'])
        ->select(DB::raw('distinct city'))
        ->get();
        foreach($sessions as $session)
        {
            $data = json_encode([
                'en'=>$session->city,
                'ar'=>$session->city,
            ], JSON_UNESCAPED_UNICODE);
            request()->en_name = $session->city;
            request()->ar_name = $session->city;

            Constant::firstOrCreate([
                'name'=>$data,
                'parent_id'=>58,
                'post_type'=>'cities',
                'slug'=>$session->city,
            ]);
        }
        /** Data Quality Test */
        /**
         select count(id) from constants where parent_id=58;
         */
    }

    private function __create_course($course, $wp_city)
    {
        $post_type = [
            10=>-1,//374//B2C
            370=>370,//B2B
        ][$course->post_type]??-5;//To review it when finished

        $data = json_encode([
            'en'=>$course->title,
            'ar'=>$course->title,
        ], JSON_UNESCAPED_UNICODE);
        request()->en_title = $course->title;
        request()->ar_title = $course->title;
        request()->en_short_title = $course->title;
        request()->ar_short_title = $course->title;

        $course_laravel = Course::firstOrCreate([
            'title'=>$data,
            'short_title'=>$data,
            'slug'=>$course->slug,
            'show_in_website'=>0,
            'post_type'=>10,
            'type_id'=>$post_type,//New
            // 'training_type'=>$post_type,//New
            'wp_migrate'=>1,
            'post_year'=>2017,
            'created_by'=>1,
            'updated_by'=>1,
            'wp_id'=>$course->id,
            'wp_city'=>$wp_city,
        ]);

        return $course_laravel;
    }

    private function __laravel_courses()
    {
        $courses = $this->conn()
        ->table('laravel_courses')
        ->where('post_type', $this->post_type)//10:B2C, 370:B2B
        ->select('*');
        return $courses;
    }

    private function __FirstOrCreateTrainingOption($laravel_id, $constant_id, $wp_city)
    {
        // dump($laravel_id.'===>'.$constant_id);
        $ss = TrainingOption::firstOrCreate([
            'course_id'=>$laravel_id,
            'constant_id'=>$constant_id,
        ],[
            'lms_id'=>-1,
            'lms_course_id'=>0,
            'created_by'=>1,
            'updated_by'=>1,
            'wp_city'=>$wp_city,
            'wp_id'=>1,
            'post_year'=>2017,
        ]);
        return $ss;
    }

    private function GenerateCartMastersNULL_laravel(){

        $carts = Cart::where('ref_master_id', 0)
        ->doesntHave('cartMaster')
        // ->withTrashed()
        ->get();

        foreach($carts as $cart)
        {
            $CartMaster = CartMaster::firstOrCreate([
                'invoice_number'=>$cart->invoice_number,
                'user_id'=>$cart->user_id,
                'payment_status'=>$cart->payment_status,
                'reference'=>'old data',
                'type_id'=>374,
                'post_year'=>date('Y', strtotime($cart->created_at))??2020,
            ],[
                'trashed_status'=>$cart->trashed_status,
                'registered_at'=>$cart->registered_at,
                'total'=>$cart->total,
                'vat'=>$cart->vat,
                'vat_value'=>$cart->vat_value,
                'total_after_vat'=>$cart->total_after_vat,
                'coin_id'=>$cart->coin_id,
                'coin_price'=>$cart->coin_price,
                'xero_invoice'=>$cart->xero_invoice,
                'xero_invoice_created_at'=>$cart->xero_invoice_created_at,
            ]);
            Cart::find($cart->id)->update([
                'master_id'=>$CartMaster->id,
                'post_year'=>$CartMaster->post_year,
            ]);
            Payment::where('old_master_id', $cart->id)->update([
                'master_id'=>$CartMaster->id,
                'post_year'=>$CartMaster->post_year,
            ]);
        }
        /** Data Quality Test */
        /**
            SELECT count(id) FROM `cart_masters` where deleted_at is NULL;
            SELECT count(id) FROM `carts` where ref_master_id=0 and deleted_at is NULL;
        */
    }

    private function FixTrainingOptionFeature()
    {
        // $trainingOptions = TrainingOption::whereIn('id', [101, 23])->get();
        $trainingOptions = TrainingOption::all();
        foreach($trainingOptions as $trainingOption)
        {
            if($trainingOption->exam_price!=0)
            {
                TrainingOptionFeature::firstOrCreate([
                    'training_option_id'=>$trainingOption->id,
                    'feature_id'=>1,
                ], [
                    'price'=>$trainingOption->exam_price,
                    'price_usd'=>$trainingOption->exam_price_usd,
                ]);
            }
            if($trainingOption->take2_price!=0)
            {
                TrainingOptionFeature::firstOrCreate([
                    'training_option_id'=>$trainingOption->id,
                    'feature_id'=>3,
                ], [
                    'price'=>$trainingOption->take2_price,
                    'price_usd'=>$trainingOption->take2_price_usd,
                ]);
            }
            if($trainingOption->exam_simulation_price_sar!=0)
            {
                TrainingOptionFeature::firstOrCreate([
                    'training_option_id'=>$trainingOption->id,
                    'feature_id'=>2,
                ], [
                    'price'=>$trainingOption->exam_simulation_price_sar,
                    'price_usd'=>$trainingOption->exam_simulation_price_usd,
                ]);
            }
            // dump($trainingOption);
        }
    }

    private function FixCartFeature()
    {
        // $carts = Cart::where('id', 28)->get();
        $carts = Cart::get();
        // dd($carts);
        foreach($carts as $cart)
        {
            if($cart->exam_price!=0)
            {
                $TrainingOptionFeature = TrainingOptionFeature::where('training_option_id', $cart->training_option_id)
                ->where('feature_id', 1)
                ->first();

                if(is_null($TrainingOptionFeature))
                {
                    $TrainingOptionFeature = TrainingOptionFeature::firstOrCreate([
                        'training_option_id'=>$cart->training_option_id,
                        'feature_id'=>1,
                    ], [
                        'price'=>$cart->exam_price,
                    ]);
                }
                $vat_value = $cart->exam_price * ($cart->vat/100);
                $total_after_vat = $cart->exam_price + $vat_value;
                CartFeature::firstOrCreate([
                    'master_id'=>$cart->id,
                    'training_option_feature_id'=>$TrainingOptionFeature->id,
                    'price'=>$cart->exam_price,
                    'vat'=>$cart->vat,
                    'vat_value'=>$vat_value,
                    'total_after_vat'=>$total_after_vat,
                ]);
            }
            if($cart->take2_price!=0)
            {
                $TrainingOptionFeature = TrainingOptionFeature::where('training_option_id', $cart->training_option_id)
                ->where('feature_id', 3)
                ->first();

                if(is_null($TrainingOptionFeature))
                {
                    $TrainingOptionFeature = TrainingOptionFeature::firstOrCreate([
                        'training_option_id'=>$cart->training_option_id,
                        'feature_id'=>3,
                    ], [
                        'price'=>$cart->take2_price,
                    ]);
                }
                $vat_value = $cart->take2_price * ($cart->vat/100);
                $total_after_vat = $cart->take2_price + $vat_value;
                CartFeature::firstOrCreate([
                    'master_id'=>$cart->id,
                    'training_option_feature_id'=>$TrainingOptionFeature->id,
                    'price'=>$cart->take2_price,
                    'vat'=>$cart->vat,
                    'vat_value'=>$vat_value,
                    'total_after_vat'=>$total_after_vat,
                ]);
            }
            // dump($trainingOption);
        }
    }

    //not used
    private function GenerateCartMastersNOTNULL_laravel(){

        $carts = Cart::whereNotNull('old_master_id')
        ->groupBy('old_master_id')
        ->get();
        foreach($carts as $cart){
            $CartMaster = CartMaster::create([
                'invoice_number'=>$cart->invoice_number,
                // 'invoice_amount'=>$cart->invoice_number,
                'user_id'=>$cart->user_id,
                'payment_status'=>$cart->payment_status,
                'reference'=>'old data',
                'type_id'=>374,
                'trashed_status'=>$cart->trashed_status,
                'registered_at'=>$cart->registered_at,
                'total'=>$cart->total,
                'vat'=>$cart->vat,
                'vat_value'=>$cart->vat_value,
                'total_after_vat'=>$cart->total_after_vat,
                'xero_invoice'=>$cart->xero_invoice,
                'xero_invoice_created_at'=>$cart->xero_invoice_created_at,
                'coin_id'=>$cart->coin_id,
                'coin_price'=>$cart->coin_price,
            ]);
            Cart::where($cart->id)->update([
                'master_id'=>$CartMaster->id,
            ]);
            Payment::where('old_master_id', $cart->id)->update([
                'master_id'=>$CartMaster->id,
            ]);
        }
    }

    private function LaravelB2b()
    {
        $B2bMasters = B2bMaster::all();
        // $payments = Payment::where('code', 'b2b')->get();
        // dd($payments);
        foreach($B2bMasters as $B2bMaster){

            $organization = Organization::find($B2bMaster->organization_id);
            $user = User::firstOrCreate([
                'email'=>$organization->email
            ],[
                'name'=>$organization->name,
                'job_title'=>$organization->job_title,
                'company'=>$organization->en_title,
            ]);

            $payment = Payment::where('master_id', $B2bMaster->id)
            ->where('code', 'b2b')
            ->first();

            $cart = Cart::where('ref_master_id', $B2bMaster->id)->with('trainingOption')->first();

            $total = 0;
            $vat_value = 0;
            $payment_status = $cart->payment_status;
            $paid_in = 0;
            if(!is_null($payment)){
                $total = round((($payment->paid_in * 100 ) / 115),2);
                $vat_value = round(($total * .15),2);
                $payment_status = $payment->payment_status;
            }

            // dump($payment->id);
            // dump($total);
            // dump($vat_value);
            // dump($total + $vat_value);
            // dd($payment->paid_in);

            $cartMaster_laravel = CartMaster::create([
                'type_id'=>370,
                'status_id'=>355,
                'user_id'=>$user->id,
                'payment_status'=>$payment_status,
                'total'=>$total,
                'vat'=>15,
                'vat_value'=>$vat_value,
                'total_after_vat'=>$paid_in,
                'invoice_number'=>Null,
                'registered_at'=>$B2bMaster->created_at,
                'wp_id'=>Null,
                'wp_city'=>Null,
                'post_year'=>2010,
            ],[
                'wp_migrate'=>100,
            ]);

            // $price = round($total/$_count, 2);
            // $vat_value = round($price*($session->vat/100),2);
            // $invoice_amount = round(($price + $vat_value),2);


            $groupInvoiceMaster = GroupInvoiceMaster::create([
                'master_id'=>$cartMaster_laravel->id,
                'course_id'=>$cart->trainingOption->course_id,
                'session_id'=>$cart->session_id,
                'organization_id'=>$organization->id,
                'price'=>$cart->total,
                'total'=>$cart->total,
                'vat'=>$cart->vat,
                'vat_value'=>$cart->vat_value,
                'total_after_vat'=>$cart->total_after_vat,
                'created_by'=>1,
                'updated_by'=>1,
                'wp_id'=>null,
                'wp_city'=>null,
                'post_year'=>date('Y', strtotime($B2bMaster->created_at)),
            ]);
        }
    }
}
