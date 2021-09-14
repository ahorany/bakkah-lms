<?php

namespace App\Exports;

use App\Models\Training\Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Database\Eloquent\Builder;

class StatisticsExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $joinTable = '(
            select *
            from users
            where id in(
                select carts.user_id
                from carts
                inner join payments on carts.id = payments.master_id
                and payments.payment_status = 68
                and payments.deleted_at is null
                group by carts.user_id
                HAVING count(carts.id) > 1
            )
            and deleted_at is null
        ) as users';
        $statistics = Cart::join(DB::raw($joinTable), function($join){
            $join->on('users.id', 'carts.user_id');
        })
        ->join('courses', 'courses.id', 'carts.course_id')
        ->select('carts.id', 'users.email', 'users.mobile', 'users.name', 'courses.title', 'carts.registered_at');

        if(!is_null(request()->user_search)) {
            $statistics = $statistics->where('name', 'like', '%'.request()->user_search.'%')
                                    ->orWhere('email', 'like', '%'.request()->user_search.'%')
                                    ->orWhere('mobile', 'like', '%'.request()->user_search.'%')
                                    ->orWhere('title', 'like', '%'.request()->user_search.'%');
        }


        if(!is_null(request()->date_from)) {
            $statistics = $statistics->whereDate('registered_at', '>=', request()->date_from);
        }
        if(!is_null(request()->date_to)) {
            $statistics = $statistics->whereDate('registered_at', '<=', request()->date_to);
        }

        $statistics = $statistics->orderBy('carts.user_id')
        ->get();

        $folder = 'statistics';
        $post_type = 'carts';
        $count = 0;
        $trash = null;
        return view('training.carts.statisticsExport', compact('statistics', 'folder', 'trash', 'count'));
    }
}
