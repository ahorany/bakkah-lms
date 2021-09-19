<?php

namespace App\Traits;

use Carbon\Carbon;

trait DateTrait
{
    public function getPublishedDateAttribute(){
        $post_date = $this->post_date;
        if(is_null($post_date)){
            $post_date = $this->created_at;
        }
        $date = Carbon::parse($post_date);
        return $date->isoFormat('D MMM Y');
    }
}
