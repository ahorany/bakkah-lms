<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SurveyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $contacts;
    public $collectortoken;

    public function __construct($contacts, $collectortoken)
    {
        $this->contacts = $contacts;
        $this->collectortoken = $collectortoken;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://az2.survey2connect.com/v1/send/multi/surveylink',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "apikey": "NWZiYjg5YzBiNWY2ZWUwYjVjYzBkYzgzLTQ5OWI2N2Uw",
                "collectortoken": "'.$this->collectortoken.'",
                "medium": "2",
                "type": "0",
                "contacts":'.$this->contacts.'
            }',
            CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            // echo $response;
        }
    }
}
