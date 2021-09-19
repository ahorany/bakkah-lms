<?php

namespace App\Models\Training;

use App\Helpers\Models\Training\CartHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class Bundle extends Model
{
    protected $guarded = [];

    public static function GetBundle(){

        $CartHelper = new CartHelper();
        $DateTimeNow = DateTimeNow();

        $bundle = self::whereNotNull('id')
        ->whereDate('start_at', '<=', $DateTimeNow)
        ->whereDate('end_at', '>=', $DateTimeNow)
        ->orderBy('start_at', 'desc')
        ->first();

        $GetCarts = $CartHelper->GetCarts()
        ->whereHas('trainingOption', function (Builder $query) use($bundle){
            $query->where('constant_id', $bundle->training_option_type_id);
        })
        ->select(DB::raw('count(id) as _count, sum(total) as _sum'))
        ->first();
        $bundle_value = 0;
        if($GetCarts->_count == $bundle->number_of_courses){
            $bundle_value = $bundle->value;
        }
        return $bundle_value;
        // dd($bundle_value);
        // dd($GetCarts);
    }
}
