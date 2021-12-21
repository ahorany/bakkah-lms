<?php


namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Admin\Upload;
use App\User;
use Illuminate\Support\Facades\URL;

class VideoController extends Controller
{

    public function find ($secret){
        return response()->json([
            // We specify which route should this method send the secure URL to
            'url' => URL::temporarySignedRoute('video_secret',
                // I have chosen that the link should expire in 5 seconds
<<<<<<< HEAD
//                now()->addSeconds(5), ['secret' => $secret]
                now()->addSeconds(1), ['secret' => $secret]
=======
                now(), ['secret' => $secret]
                // now()->addSeconds(1), ['secret' => $secret]
>>>>>>> de062304a562c27fcc151db20ab6e74a64053a5b
            )
        ],201);
    }

    // This is the method which will send the secure URL to the user
    public function playVideoWithSecret ($secret){
        // Because '&&' used as the separator in the query string
        $secrets = explode("&&", $secret);
        $video_id = $secrets[0];
        $user_id = $secrets[1];

        $user = User::where('id',$user_id)->firstOrFail();
        // I ran some tests with $user, basically, has he the right to get the link? etc...

        $video = Upload::where('id', $video_id)->first();



        return response()->file(  public_path('upload/files/videos/'. $video->file));
    }


}
