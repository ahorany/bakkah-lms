<?php


namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Training\Upload;
use App\User;
use Illuminate\Support\Facades\URL;

class PreventDownloadFileController extends Controller
{

    public function find ($secret){
        return response()->json([
            // We specify which route should this method send the secure URL to
            'url' => URL::temporarySignedRoute('file_secret',
                // I have chosen that the link should expire in 5 seconds
                now()->addSeconds(1), ['secret' => $secret]
            )
        ],201);
    }

    // This is the method which will send the secure URL to the user
    public function FileWithSecret ($secret){
        // Because '&&' used as the separator in the query string
        $secrets = explode("&&", $secret);
        $file_id = $secrets[0];
        $user_id = $secrets[1];
        $type = $secrets[2];

        $user = User::where('id',$user_id)->firstOrFail();


        $file = Upload::where('id', $file_id)->first();

        $path = "";
        switch ($type){
            case 'presentation': $path = 'upload/files/presentations/'; break;
            case 'scorm'       : $path = 'upload/files/scorms/'; break;
        }

        if(!$path && $file->file->extension != 'pdf') abort(404);

         return response()->file( public_path($path. $file->file));
    }


}
