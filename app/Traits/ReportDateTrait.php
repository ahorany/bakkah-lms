<?php

namespace App\Traits;

use Carbon\Carbon;

trait ReportDateTrait
{
    public function getPublishedDateAttribute(){
        $date = Carbon::parse($this->created_at);
        return $date->isoFormat('D MMM Y');
    }
}
