<?php

namespace App\Models;
use App\Eloquent;
use Jenssegers\Agent\Agent;
use App\Constant;
use DB;
use Illuminate\Pagination\LengthAwarePaginator;

class Paginator extends Eloquent
{

    static function GetPaginator($items)
    {

        $total = count($items); // total count of the set, this is necessary so the paginator will know the total pages to display
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = PAGINATE; // how many items you want to display per page?
        $offset = ($currentPage - 1) * $perPage; // get the offset, how many items need to be "skipped" on this page
        // dump($currentPage);dump('start : '.$offset);dump('length : '.$perPage);dump($currentPage);
        $items = array_slice($items, $offset, $perPage); // the array that we actually pass to the paginator is sliced
        // dd($items);
        return new LengthAwarePaginator($items, $total, $perPage, $currentPage, [
            'path' => request()->url(),
            'query' => request()->query()
        ]);

    }

}
