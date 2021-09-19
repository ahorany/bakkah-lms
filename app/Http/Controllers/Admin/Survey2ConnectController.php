<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Survey2connect;
use App\SurveyAnswer;
use App\SurveyGeneral;
use App\SurveyOption;
use App\SurveyQuestion;

class Survey2ConnectController extends Controller
{
    public function generalQuestions(){

        $response = $this->getData('6054cebef97b95600ab1a8cf');

        $responseData = json_decode($response, true);
        $responseData['data'] =  array_slice($responseData['data'],2);

        foreach($responseData['data'] as $data){
            $data =  array_slice($data,0,19);
            SurveyGeneral::updateOrCreate([
                'respondent_unique_id' => $data[0],
            ],[
                'respondent_unique_id' => $data[0],
                'response_status' => $data[1],
                'language' => $data[2],
                'collector_name' => $data[3],
                'collector_unique_id' => $data[4],
                'start_date' => $data[5],
                'end_date' => $data[6],
                'time_duration' => $data[7],
                'ip_address' => $data[8],
                'device_information' => $data[9],
                'email_addreess' => $data[10],
                'first_name' => $data[11],
                'last_name' => $data[12],
                'mobile' => $data[13],
                'external_unique_id' => $data[14],
                'location_latitude' => $data[15],
                'location_longitude' => $data[16],
                'by_ticket' => $data[17],
                'ticket_generated' => $data[18],
            ]);
        }
        dd('general');
    }

    public function surveyQuestions(){

        $surveys = Survey2connect::with('survey_questions.survey_options.survey_answers')
        ->where('status', 1)
        ->get();
        foreach($surveys as $survey){
            $this->Body($survey);
        }
        dd('Done');
    }

    private function Body($survey){

        $response = $this->getData($survey->token);
        $responseData = json_decode($response, true);

        $questions =  array_slice($responseData['data'], 0, 1);
        $options =  array_slice($responseData['data'], 1, 1);
        $data =  array_slice($responseData['data'], 2);

        $questions1 =  array_slice($questions[0], 19);
        $options1 =  array_slice($options[0], 19);

        foreach($questions1 as $key => $question)
        {
            $SurveyQuestion = SurveyQuestion::firstOrCreate([
                'master_id' => $survey->id,
                'title' => $question,
            ]);
            if($SurveyQuestion->title == $question)
            {
                SurveyOption::firstOrCreate([
                    'master_id' => $SurveyQuestion->id,
                    'rank' => $key,
                ], [
                    'title' => $options1[$key],
                ]);
            }
        }

        foreach($data as $key => $value)
        {
            $answers = array_slice($value, 19);
            foreach($answers as $i => $v){
                $SurveyOption = SurveyOption::where('rank', $i)->first();
                SurveyAnswer::firstOrCreate([
                    'master_id' => $SurveyOption->id,
                    'rank' => $key,
                ], [
                    'title' => $v,
                ]);
            }
        }
    }

    private function getData($token){

        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://export.az2.survey2connect.com/v1/api/responses/'.$token .'/csv',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
          "skip": 0,
          "limit": 1000,
          "collectorIds": [
            "6086938a8e9547064936f2a6"
          ],
          "status": [
            "1"
          ],
          "labels": true
        }',
          CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer eyJraWQiOiJzM3E1OWF2cFdIcXVKa0I0MjRpcFpqQVN0dXAzQzFkZEhVM3k2TjNZSXRjPSIsImFsZyI6IlJTMjU2In0.eyJzdWIiOiI4NjZjMTNkNS1lMmQxLTQzMjctYWZhNS0wM2NkMGUwNmRjYzciLCJ6b25laW5mbyI6InByb2RfYXoyIiwiZW1haWxfdmVyaWZpZWQiOnRydWUsImN1c3RvbTpwYXNzd29yZFRvQmVSZXNldCI6InRydWUiLCJpc3MiOiJodHRwczpcL1wvY29nbml0by1pZHAuYXAtc291dGgtMS5hbWF6b25hd3MuY29tXC9hcC1zb3V0aC0xX3NqU3NzYms5diIsImN1c3RvbTp1SWQiOiI2MDU0ZDRkM2Y5N2I5NTYwMGFiMWFiYWMiLCJjb2duaXRvOnVzZXJuYW1lIjoiODY2YzEzZDUtZTJkMS00MzI3LWFmYTUtMDNjZDBlMDZkY2M3IiwiYXVkIjoiODVrOHVvZGo4MGlrNTNvYXRwNHJxYmxiYyIsImV2ZW50X2lkIjoiMTM0NGVkYTYtMWQ1NS00YmY1LWI5NGQtMmFhZWMxM2U1Y2YzIiwidG9rZW5fdXNlIjoiaWQiLCJhdXRoX3RpbWUiOjE2MzE2ODg0ODYsImN1c3RvbTpzdGF0dXMiOiJhY3RpdmUiLCJuYW1lIjoiSVQiLCJleHAiOjE2MzE3NzQ4ODYsImN1c3RvbTpyb2xlIjoibWVtYmVyIiwiaWF0IjoxNjMxNjg4NDg2LCJlbWFpbCI6ImFhbGhvcmFueUBiYWtrYWgubmV0LnNhIn0.UAKSkuCUqESx-aAaltSEdMjHj7oNL6dyz3c73zACKN1KrXLYBnJeSO8ld4XNGAwkek81D9zL_P1Or5qKdu0cEYgmj5el7RM4P_EWvyuRchC-qDdRfE8VQvk4-S6OjzztoCvrdG-bxkYrcS79YAnFomRKYoB1gBBJUT-hF2gqsEWpcqwqasb8ko1upUw1eWvERjLB2rtGOhQZdI0QAO-dL1RV6C99jY_Q2kMj7mcqZwtB_g0esoEi9TeVB-Qr1ykjXq7uE8xZe4Pm8166riuP5ySQZi5gjBH9NXOJ4iZp1rl389VxmQGEeYgRmgU7AolT6VXxQhd2DCunicpYic1e2g',
            'Content-Type: application/json'
          ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

      return $response;
    }
}
