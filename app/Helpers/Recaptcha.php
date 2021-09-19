<?php

namespace App\Helpers;

class Recaptcha {

    public static function script(){

        if(env('NODE_ENV')=='production'){
            ?>
            <script src="https://www.google.com/recaptcha/api.js?render=<?= env('reCAPTCHA_site_key'); ?>"></script>
            <?php
        }
    }

    public static function execute($action='homepage'){

        if(env('NODE_ENV')=='production'){
            ?>
            <script>
                grecaptcha.ready(function() {
                    grecaptcha.execute("<?= env('reCAPTCHA_site_key'); ?>", {action: "<?= $action; ?>"}).then(function(token) {
                        // Add your logic to submit to your backend server here.
                        document.getElementById('recaptcha_response').value=token;
                    });
                });
            </script>
            <input type="hidden" id="recaptcha_response" name="recaptcha_response">
            <?php
        }
    }

    public static function run(){
        if(env('NODE_ENV')!='production')
            return true;

        if(request()->has('recaptcha_response')) {
            $result = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".env('reCAPTCHA_secret_key')."&response=".request()->recaptcha_response);
            $r = json_decode($result);
            if ($r->success == true && $r->score > 0.5) {
                return true;
            }
        }
        return false;
    }
}
