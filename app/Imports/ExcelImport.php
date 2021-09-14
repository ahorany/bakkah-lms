<?php

namespace App\Imports;

use App\Constant;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\User;
use App\Models\Training\Cart;
use App\Helpers\Models\Training\SessionHelper;
use App\Helpers\Models\UserHelper;
use App\Models\Training\CartMaster;
use Modules\CRM\Entities\Organization;

class ExcelImport implements ToCollection
{
    protected $organization_id;
    protected $rfp_group_id;
    protected $cart_master_id;
    protected $course_id;
    protected $session_id;

    public function __construct($organization_id = 0, $rfp_group_id = 0, $cart_master_id = 0, $course_id = 0, $session_id = 0) {
        $this->organization_id = $organization_id;
        $this->rfp_group_id = $rfp_group_id;
        $this->cart_master_id = $cart_master_id;
        $this->course_id = $course_id;
        $this->session_id = $session_id;
    }

    public function collection(Collection $collection)
    {
        $coin_id = GetCoinId();
        $coin_price = GetCoinPrice();

        // dd($this->session_id);
        request()->training_option_id = -1;
        request()->course_id = $this->course_id;
        $SessionHelper = new SessionHelper();
        $session = $SessionHelper->TrainingOption([0, 1])->where('session_id', $this->session_id)->first();

        if(!is_null($session)){
            request()->training_option_id = $session->training_option_id;
            $SessionHelper->SetCourse($session);
            // dd($session);
            // $Discount = $SessionHelper->Discount();
            $vat = $SessionHelper->VAT();
            $session_exam_price = $SessionHelper->ExamPrice();
            $discount_value = $SessionHelper->DiscountValue();
            $vat_value = $SessionHelper->VATFortPriceWithExamPrice();
            $sub_total = $SessionHelper->PriceWithExamPrice();
            $total_after_vat = $SessionHelper->PriceAfterDiscountWithExamPriceAfterVAT();

            // dd($collection);
            // dd('dddd');
            // if(auth()->check()){
            //     if(auth()->user()->id==2){
            //         dd($collection->count());
            //     }
            // }
            if($collection->count() > 1){
                foreach($collection as $key=>$value)
                {
                    if($key>0)
                    {
                        if(empty($value[1])){
                            continue;
                        }
                        request()->en_name = $value[0];
                        request()->ar_name = $value[0];
                        $country   = Constant::where("name", "like", "%".$value[4]."%")->first();
                        $gender = Constant::where("name", "like", "%".$value[5]."%")->first();
                        $company = Organization::find($this->organization_id)->first();

                        $args = [
                            'name' => null,
                            'mobile' => $value[2],
                            'job_title' => $value[3],
                            'country' => $value[4],
                            'country_id' => $country->id??58,
                            'gender_id' => $gender->id??43,
                            'company' => $company->en_title??null,
                            'user_type' => 41,
                            'locale'=>app()->getLocale(),
                        ];

                        $user = User::where('email', $value[1])->first();
                        $user_lms_args = [];
                        if(is_null($user)){
                            $UserHelper = new UserHelper();
                            $user_lms = $UserHelper->GenerateUserLMS($value[1]);
                            $user_lms_args = [
                                'username_lms'=>$user_lms['username'],
                                'password_lms'=>$user_lms['password'],
                            ];
                        }
                        $args = array_merge($args, $user_lms_args);

                        $user = User::updateOrCreate([
                            'email' => $value[1],
                        ], $args);
                        $user_id = $user->id;

                        // $user = User::where('email', $value[1])->orWhere('main_email', $value[1])->first();
                        // $user_lms_args = [];
                        // $new_email = $value[1];
                        // if(is_null($user)){
                        //     $UserHelper = new UserHelper();
                        //     // $user_lms = $UserHelper->GenerateUserLMS($value[1]);
                        //     $email_array      =  explode('@', $value[1]);
                        //     $username_from_email  =  str_replace(".","",$email_array['0']);
                        //     $e_portal_username    = strtolower($username_from_email);
                        //     $e_portal_password    = $e_portal_username.'@'.$UserHelper->random_str(3);
                        //     $new_email    = strtolower($e_portal_username.'_b2b_bakkah__'.'@'.$email_array['1']);

                        //     $user_lms_args = [
                        //         'username_lms'=>$e_portal_username,
                        //         'password_lms'=>$e_portal_password,
                        //         'main_email'=>$value[1],
                        //     ];

                        //     // $user_lms_args = [
                        //     //     'username_lms'=>$user_lms['username'],
                        //     //     'password_lms'=>$user_lms['password'],
                        //     //     'main_email'=>$value[1],
                        //     // ];
                        // }else{
                        //     $new_email = $user->email;
                        // }
                        // $args = array_merge($args, $user_lms_args);

                        // $user = User::updateOrCreate([
                        //     'email' => $new_email,
                        //     // 'email' => $value[1],
                        // ], $args);
                        // $user_id = $user->id;

                        $cartMaster = CartMaster::find($this->cart_master_id);
                        // $master_id__field = ($cartMaster->type_id==372)?'group_invoice_id':'master_id';

                        // $cart = Cart::where($master_id__field, $this->cart_master_id)
                        $cart = Cart::where('master_id', $this->cart_master_id)
                                    ->where('user_id', $user_id)
                                    ->first();
                        $cart_args = [
                            'master_id' => $this->cart_master_id,
                            'session_id' => $this->session_id,
                            'training_option_id' => $session->training_option_id,
                            'course_id' => $this->course_id,
                            'user_id' => $user_id,
                            'price' => NumberFormat($session->session_price),
                            'discount_id' => $session->discount_id,
                            'discount' => NumberFormat($session->discount_value),
                            'discount_value' => NumberFormat($discount_value),
                            'exam_price' => NumberFormat($session_exam_price),
                            'take2_price' => 0,
                            'exam_simulation_price' => NumberFormat($session->exam_simulation_price),
                            'total' => NumberFormat($sub_total),
                            'vat' => NumberFormat($vat),
                            'vat_value' => NumberFormat($vat_value),
                            'total_after_vat' => NumberFormat($total_after_vat),
                            'coin_id'=>$coin_id,
                            'coin_price'=>$coin_price,
                            'registered_at'=>DateTimeNow(),
                            'locale'=>app()->getLocale(),
                        ];
                        if(is_null($cart))
                        {
                            $cart_args_create = array_merge($cart_args, [
                                'status_id' => 327, //51
                                'invoice_number' => date("His").rand(1234, 9632),
                                'payment_status'=>63,
                            ]);
                            $cart = Cart::create($cart_args_create);
                        }
                        else
                        {
                            $trying_count = $cart->trying_count + 1;
                            $cart_args_update = array_merge($cart_args, [
                                'trying_count'=>$trying_count,
                            ]);
                            Cart::where('id', $cart->id)->update($cart_args_update);
                        }
                        // dd($cart->id);
                        // array_push($this->users_ids, $user_id);
                    } //if($key>0){
                } // foreach
            }
        }
    }
}
