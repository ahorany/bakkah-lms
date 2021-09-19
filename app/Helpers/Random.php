<?php

namespace App\Helpers;

class Random {

    public function Generate(){
        $bytes = strtolower(random_bytes(5));
        return 'bakkah-'.bin2hex($bytes);
    }
}
