<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Pipedrive;
use App\PipedriveActivity;
use App\PipedriveDealField;
use App\PipedriveDealFieldOption;
use App\PipedriveOption;
use App\PipedriveOrganization;
use App\PipedriveProduct;
use App\PipedriveProductAttached;
use App\PipedriveProductPrice;
use Illuminate\Http\Request;

class PipedriveController extends Controller
{
    public function pipedrive_product_attacheds__run(){

        // dd('test');
        $pipedrives = Pipedrive::skip(1100)->take(100)->get();
        foreach($pipedrives as $pipedrive)
        {
            $this->pipedrive_product_attacheds($pipedrive->pipedrive_id);
        }
        dd('done11');
    }

    public function pipedrive(){

        $origin_fields_array = ['id', 'creator_user_id', 'user_id', 'person_id', 'org_id', 'stage_id', 'title', 'value'
        , 'currency', 'add_time', 'update_time', 'stage_change_time', 'active', 'deleted', 'status', 'probability', 'next_activity_date'
        , 'next_activity_time', 'next_activity_id', 'last_activity_id', 'last_activity_date', 'lost_reason', 'visible_to', 'close_time'
        , 'pipeline_id', 'won_time', 'first_won_time', 'lost_time', 'products_count', 'files_count', 'notes_count', 'followers_count'
        , 'email_messages_count', 'activities_count', 'done_activities_count', 'undone_activities_count', 'participants_count'
        , 'expected_close_date', 'last_incoming_mail_time', 'last_outgoing_mail_time', 'label', 'stage_order_nr', 'person_name'
        , 'org_name', 'next_activity_subject', 'next_activity_type', 'next_activity_duration', 'next_activity_note', 'group_id', 'group_name'
        , 'formatted_value', 'weighted_value', 'formatted_weighted_value', 'weighted_value_currency', 'rotten_time', 'owner_name'
        , 'cc_email', 'org_hidden', 'person_hidden', 'average_time_to_won', 'average_stage_progress', 'age', 'stay_in_pipeline_stages'
        , 'last_activity', 'next_activity'];

        $response = $this->getApi('https://bakkahinc.pipedrive.com/v1/deals?api_token=54d66f5e6da01d7665e04b0f9e63fc56c9726527&start=1000&limit=500');
        $responseData = json_decode($response, true);
        // dump($responseData['data']);
        foreach($responseData['data'] as $data){

            Pipedrive::updateOrCreate([
                'pipedrive_id'=>$data['id'],
            ], [
                'title'=>$data['title'],
                'value'=>$data['value'],
                'weighted_value'=>$data['weighted_value'],
                'stage_id'=>$data['stage_id'],
                'status'=>$data['status'],
                'probability'=>$data['probability'],
                'visible_to'=>$data['visible_to'],
                'pipeline_id'=>$data['pipeline_id'],
                'products_count'=>$data['products_count'],
                'followers_count'=>$data['followers_count'],
                'label'=>$data['label'],
                'stage_order_nr'=>$data['stage_order_nr'],
                'active'=>$data['active'],
                'deleted'=>$data['deleted'],
                'add_time'=>$data['add_time'],
                'update_time'=>$data['update_time'],
                'stage_change_time'=>$data['stage_change_time'],
                'files_count'=>$data['files_count'],
                'renewal_type'=>$data['renewal_type'],
                'org_name'=>$data['org_name'],
                'weighted_value_currency'=>$data['weighted_value_currency'],
                'rotten_time'=>$data['rotten_time'],
                'owner_name'=>$data['owner_name'],
                'cc_email'=>$data['cc_email'],
                'org_hidden'=>$data['org_hidden'],
                'person_hidden'=>$data['person_hidden'],
            ]);

            // $start = 'label';
            foreach($data as $k=>$v)
            {
                if(!in_array($k, $origin_fields_array))
                {
                    if(!is_null($v)){
                        PipedriveOption::updateOrCreate([
                            'master_id'=>$data['id'],
                            'key'=>$k,
                            'value'=>$v,
                            'option_type'=>'leads',
                        ]);
                        // dump($k);
                        // dump($v);
                    }
                }
            }
        }
        dd('test');
    }

    public function organization(){

        // dump('test');
        $origin_fields_array = ['e41dcf09dfcdcc723705272fd7ffdf050d64bbdb', 'ab4d7fae02ccfd37d5dc338b6f8dec05f9c4a462', '01070f5430bb6b343828bd8c57204bebd97dab31'
        , '95eac977603a9b2b5dc25f797a7dc42452961abf', 'ce16f0ce42f51b77ebdc385357fb4f9f47f9c352', 'e2522c7b5aa1b08ff685ab30b46864de73f22ea3'
        , '95cd0f0655faa4b87a41b759364fda0036c0fe32', '95345f3275f2a062c6cb23585a82c343fce2d9bb'];

        $response = $this->getApi('https://bakkahinc.pipedrive.com/v1/organizations?api_token=54d66f5e6da01d7665e04b0f9e63fc56c9726527&start=800&limit=100');
        // $response = $this->getApi('https://bakkahinc.pipedrive.com/v1/organizations/1?api_token=54d66f5e6da01d7665e04b0f9e63fc56c9726527');
        $responseData = json_decode($response, true);
        // dd($responseData['data']);
        foreach($responseData['data'] as $data){

            $id = $data['id'];
            PipedriveOrganization::updateOrCreate([
                'pipedrive_id'=>$data['id'],
            ], [
                'company_id'=>$data['company_id'],
                'name'=>$data['name'],
                'open_deals_count'=>$data['open_deals_count'],
                'related_open_deals_count'=>$data['related_open_deals_count'],
                'closed_deals_count'=>$data['closed_deals_count'],
                'related_closed_deals_count'=>$data['related_closed_deals_count'],
                'add_time'=>$data['add_time'],
                'update_time'=>$data['update_time'],
                'visible_to'=>$data['visible_to'],
                'next_activity_date'=>$data['next_activity_date'],
                // 'next_activity_time'=>$data['next_activity_time'],
                'next_activity_id'=>$data['next_activity_id'],
                'last_activity_id'=>$data['last_activity_id'],
                'last_activity_date'=>$data['last_activity_date'],
                'label'=>$data['label'],
                'address'=>$data['address'],
                'address_subpremise'=>$data['address_subpremise'],
                'address_street_number'=>$data['address_street_number'],
                'address_route'=>$data['address_route'],
                'address_sublocality'=>$data['address_sublocality'],
                'address_locality'=>$data['address_locality'],
                'address_admin_area_level_1'=>$data['address_admin_area_level_1']??null,
                'address_admin_area_level_2'=>$data['address_admin_area_level_2']??null,
                'address_country'=>$data['address_country'],
                'address_postal_code'=>$data['address_postal_code'],
                'address_formatted_address'=>$data['address_formatted_address'],
                'cc_email'=>$data['cc_email'],
                'owner_name'=>$data['owner_name'],
            ]);

            $response1 = $this->getApi('https://bakkahinc.pipedrive.com/v1/organizations/'.$data['id'].'?api_token=54d66f5e6da01d7665e04b0f9e63fc56c9726527');
            $responseData1 = json_decode($response1, true);
            foreach($responseData1['data'] as $key => $value)
            {
                if($key=='last_activity'){
                    $this->_PipedriveActivity($value, $key, $id);
                }
                else if($key=='next_activity'){
                    $this->_PipedriveActivity($value, $key, $id);
                }
            }
            // $start = 'label';
            // foreach($data as $k=>$v)
            // {
            //     if(in_array($k, $origin_fields_array))
            //     {
            //         if(!is_null($v)){
            //             PipedriveOption::updateOrCreate([
            //                 'master_id'=>$data['id'],
            //                 'key'=>$k,
            //                 'value'=>$v,
            //                 'option_type'=>'organizations',
            //             ]);
            //             // dump($k);
            //             // dump($v);
            //         }
            //     }
            // }
            // if($data['last_activity']=='last_activity'){

            // }
            // dump($data['last_activity']);
        }
        dd('test');
    }

    private function _PipedriveActivity($data, $post_type, $id)
    {
        if(!is_null($data)){
            PipedriveActivity::updateOrCreate([
                'pipedrive_id'=>$data['id'],
            ], [
                'master_id'=>$id,
                'company_id'=>$data['company_id'],
                'user_id'=>$data['user_id'],
                'done'=>$data['done'],
                'type'=>$data['type'],
                'due_date'=>$data['due_date'],
                'add_time'=>$data['add_time'],
                'marked_as_done_time'=>$data['marked_as_done_time'],
                'notification_language_id'=>$data['notification_language_id'],
                'subject'=>$data['subject'],
                'org_id'=>$data['org_id'],
                'person_id'=>$data['person_id'],
                'deal_id'=>$data['deal_id'],
                'lead_id'=>$data['lead_id'],
                'lead_title'=>$data['lead_title'],
                'active_flag'=>$data['active_flag'],
                'update_time'=>$data['update_time'],
                'update_user_id'=>$data['update_user_id'],
                'created_by_user_id'=>$data['created_by_user_id'],
                'series'=>$data['series'],
                'org_name'=>$data['org_name'],
                'person_name'=>$data['person_name'],
                'deal_title'=>$data['deal_title'],
                'owner_name'=>$data['owner_name'],
                'person_dropbox_bcc'=>$data['person_dropbox_bcc'],
                'deal_dropbox_bcc'=>$data['deal_dropbox_bcc'],
                'assigned_to_user_id'=>$data['assigned_to_user_id'],
                'type_name'=>$data['type_name'],
                'file'=>$data['file'],
                'post_type'=>$post_type,
            ]);
        }
    }

    public function PipedriveDealField($post_type){

        // dd($post_type);
        $url = 'https://bakkahinc.pipedrive.com/v1/dealFields?api_token=54d66f5e6da01d7665e04b0f9e63fc56c9726527';
        if($post_type=='products'){
            $url = 'https://bakkahinc.pipedrive.com/v1/productFields?api_token=54d66f5e6da01d7665e04b0f9e63fc56c9726527';
        }
        else if($post_type=='organizations'){
            $url = 'https://bakkahinc.pipedrive.com/v1/organizationFields?api_token=54d66f5e6da01d7665e04b0f9e63fc56c9726527';
        }

        $response = $this->getApi($url);
        $responseData = json_decode($response, true);
        // dd($responseData['data']);
        foreach($responseData['data'] as $data){

            PipedriveDealField::updateOrCreate([
                'pipedrive_id'=>$data['id'],
            ], [
                'key'=>$data['key'],
                'name'=>$data['name'],
                'order_nr'=>$data['order_nr']??null,
                'field_type'=>$data['field_type']??null,
                'json_column_flag'=>$data['json_column_flag']??null,
                'add_time'=>$data['add_time']??null,
                'update_time'=>$data['update_time']??null,
                'active_flag'=>$data['active_flag']??null,
                'edit_flag'=>$data['edit_flag']??null,
                'index_visible_flag'=>$data['index_visible_flag']??null,
                'details_visible_flag'=>$data['details_visible_flag']??null,
                'add_visible_flag'=>$data['add_visible_flag']??null,
                'important_flag'=>$data['important_flag']??null,
                'bulk_edit_allowed'=>$data['bulk_edit_allowed']??null,
                'searchable_flag'=>$data['searchable_flag']??null,
                'filtering_allowed'=>$data['filtering_allowed']??null,
                'sortable_flag'=>$data['sortable_flag']??null,
                'post_type'=>$post_type,
            ]);
            if(isset($data['options']))
            {
                foreach($data['options'] as $data_option)
                {
                    PipedriveDealFieldOption::updateOrCreate([
                        'master_id'=>$data['id'],
                        'pipedrive_id'=>$data_option['id'],
                    ], [
                        'label'=>$data_option['label'],
                    ]);
                }
            }
        }
        dd('PipedriveDealField');
    }

    public function products(){

        $response = $this->getApi('https://bakkahinc.pipedrive.com/v1/products?api_token=54d66f5e6da01d7665e04b0f9e63fc56c9726527&start=0&limit=500');
        $responseData = json_decode($response, true);

        foreach($responseData['data'] as $data){

            PipedriveProduct::updateOrCreate([
                'pipedrive_id'=>$data['id'],
            ], [
                'name'=>$data['name'],
                'code'=>$data['code'],
                'description'=>$data['description'],
                'unit'=>$data['unit'],
                'tax'=>$data['tax'],
                'category'=>$data['category'],
                'active_flag'=>$data['active_flag'],
                'selectable'=>$data['selectable'],
                'first_char'=>$data['first_char'],
                'visible_to'=>$data['visible_to'],
                'add_time'=>$data['add_time'],
                'update_time'=>$data['update_time'],
            ]);
            if(isset($data['prices']))
            {
                foreach($data['prices'] as $data_option)
                {
                    PipedriveProductPrice::updateOrCreate([
                        'pipedrive_product_id'=>$data['id'],
                        'pipedrive_id'=>$data_option['id'],
                    ], [
                        'price'=>$data_option['price'],
                        'currency'=>$data_option['currency'],
                        'cost'=>$data_option['cost'],
                        'overhead_cost'=>$data_option['overhead_cost'],
                    ]);
                }
            }
        }
        dd('products');
    }

    public function pipedrive_product_attacheds($deal_id){

        $response = $this->getApi('https://bakkahinc.pipedrive.com/v1/deals/'.$deal_id.'/products?api_token=54d66f5e6da01d7665e04b0f9e63fc56c9726527');
        $responseData = json_decode($response, true);

        if(!is_null($responseData['data']))
        {
            foreach($responseData['data'] as $data){

                PipedriveProductAttached::updateOrCreate([
                    'pipedrive_id'=>$data['id'],
                    'deal_id'=>$deal_id,
                ], [
                    'order_nr'=>$data['order_nr'],
                    'product_id'=>$data['product_id'],
                    'product_variation_id'=>$data['product_variation_id'],
                    'item_price'=>$data['item_price'],
                    'discount_percentage'=>$data['discount_percentage'],
                    'duration'=>$data['duration'],
                    'duration_unit'=>$data['duration_unit'],
                    'sum_no_discount'=>$data['sum_no_discount'],
                    'sum'=>$data['sum'],
                    'currency'=>$data['currency'],
                    'enabled_flag'=>$data['enabled_flag'],
                    'add_time'=>$data['add_time'],
                    'last_edit'=>$data['last_edit'],
                    'comments'=>$data['comments'],
                    'active_flag'=>$data['active_flag'],
                    'tax'=>$data['tax'],
                    'name'=>$data['name'],
                    'sum_formatted'=>$data['sum_formatted'],
                    'quantity_formatted'=>$data['quantity_formatted'],
                    'quantity'=>$data['quantity'],
                ]);
            }
        }
    }

    private function getApi($CURLOPT_URL){

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $CURLOPT_URL,
            // CURLOPT_URL => 'https://bakkahinc.pipedrive.com/v1/deals?api_token=54d66f5e6da01d7665e04b0f9e63fc56c9726527&start='.$start.'&limit='.$limit,
            // &stage_id=13
            // CURLOPT_URL => 'https://bakkahinc.pipedrive.com/v1/deals?api_token=54d66f5e6da01d7665e04b0f9e63fc56c9726527',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Cookie: __cf_bm=f57157e697e45f2ef7a73b45aae66ca9040a843b-1623561002-1800-AbdJoP7aTMNhAJMh7rleSosetWY0gTzlqMIpCMwoVY5TU94/AdRDTt3NNAiDbPE0uV7FJKRcD5/hMzIQyddcGHE='
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

    public function add_person(){

        return view('admin.pipedrives.add_person');
    }

    public function add_deal_post(){

        if(request()->has('email')){

            // $api_token = 'c9a00b2e0bf026f01a00f7f16a4c596ebc1b2901';//sandbox
            $api_token = '54d66f5e6da01d7665e04b0f9e63fc56c9726527';//live

            $person_id = $this->curl_person($api_token);
            if(!is_null($person_id)){
                $this->curl_deal($api_token, $person_id);
            }
        }
    }

    private function curl_person($api_token){

        $curl = curl_init();

        $name = request()->name??'Client';

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://bakkahinc.pipedrive.com/v1/persons?api_token='.$api_token,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'name='.$name.'&owner_id=12233403&email='.request()->email.'&phone='.request()->mobile.'&visible_to=1',
        // CURLOPT_POSTFIELDS => 'name=Test1234444445&owner_id=12233403&email=test1113@hotmail.com&phone=05925724871&visible_to=1',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded',
            'Cookie: __cf_bm=a084c6eed69443c4a763b39fc317ca9b42a9a23a-1626331975-1800-AQvgeKuSNSWjygNuB6Gbwz4FyGA7iUEX+1JCJXfA/fn7k2cHM7pMt7N5CEuYnlllu3xx1Xh35pbQKU7ynNtmBaE='
        ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $responseData = json_decode($response, true);
        if($response){
            // dump($responseData['success']);
            // dump($responseData['data']['id']);
            return $responseData['data']['id'];
        }
        return null;
    }

    private function curl_deal($api_token, $person_id=1891){

        $user_id = 9447640;//Nedal Al-Farhan    //nalfarhan@bakkah.net.sa
        $add_time = now();

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://bakkahinc-sandbox.pipedrive.com/v1/deals?api_token='.$api_token,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'title='.request()->request_details.'&value=0&currency=SAR&user_id='.$user_id.'&person_id='.$person_id.'&stage_id=1&status=open&visible_to=1&add_time='.$add_time,
        // CURLOPT_POSTFIELDS => 'title=For%20Corporates%20request%20Name%20test%201&value=0&currency=SAR&user_id=9447640&person_id=1891&stage_id=1&status=open&visible_to=1&add_time=2021-07-20',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded',
            'Cookie: __cf_bm=dc5b3eb77a08e1da1725ab2984323f7fcf66e6ff-1626335427-1800-AXXEUrm1M2iXYuXJQdC3kAVQwc9VCaGNu/f6qT19TH75Ofme4ABmTIPiH0p67v+Yaxt0WTUYvrcgTJVkxPSJ2IE='
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }
}
