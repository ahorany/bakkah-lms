<?php

namespace App\Helpers;

class Mailchimp {

    function sync($user=null, $email=null, $LNAME=null) {
        // API to mailchimp ########################################################
        $list_id = '275be1864d';
        $authToken = '41f340d5dd5d6efd3da3b14201e7740a-us19';
        // The data to send to the API

        $FNAME = '';
        // $LNAME = '';
        if(!is_null($user)){
            $email = $user->email;
            $FNAME = $user->en_name;
            // $LNAME = "Course Registration";
        }
        $postData = array(
            "email_address" => $email,
            "status" => "subscribed",
            "merge_fields" => array(
            "FNAME"=> $FNAME,
            "LNAME"=> $LNAME,
            // "PHONE"=> 'Course Registration'
            )
        );

        // Setup cURL
        $ch = curl_init('https://us19.api.mailchimp.com/3.0/lists/'.$list_id.'/members/');
        curl_setopt_array($ch, array(
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HTTPHEADER => array(
                'Authorization: apikey '.$authToken,
                'Content-Type: application/json'
            ),
            CURLOPT_POSTFIELDS => json_encode($postData)
        ));
        // Send the request
        $response = curl_exec($ch);
        return $response;
        // #######################################################################
    }

}
