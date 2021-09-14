<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Active;
use App\Http\Controllers\Controller;
use App\Models\Admin\Partner;
use App\Models\Training\Course;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PerformanceController extends Controller
{
    public function __construct()
    {
        Active::$folder = 'performance';
    }

    public function index()
    {
        $post_type = GetPostType('performance');
        $trash = GetTrash();
        $partners = Partner::whereNotNull('id')->where('post_type','partners')->get();
        $products = Course::whereNotNull('id')
        ->whereHas('partner.agreement', function(Builder $builder){
            if(request()->has('status') && request()->status != -1){
                $builder->where('is_active', request()->status == 'active' ? 1 : 0);
            }
        })
        ->with(['partner.agreement']);

        if(!is_null(request()->partner) && request()->partner != -1) {
            $products = $products->whereHas('partner', function($q){
                return $q->where('id',request()->partner);
            });
        }

        $products_for_combo = $products->get();
        if(!is_null(request()->product) && request()->product != -1) {
            $products = $products->where('id', request()->product);
        }

        $products = $products->orderBy('partner_id');


        // $args = [];
        // $product_date_join = "";
        // if(request()->has('product_date_from') && !is_null(request()->product_date_from)){
        //     $product_date_join = " inner join partners on partners.id = courses.partner_id inner join agreements on agreements.partner_id = partners.id";
        //     $product_date_from = request()->product_date_from;
        //     $args = array_merge($args, ['signing_date' => $product_date_from,]);

        //     if(request()->has('product_date_to') && !is_null(request()->product_date_to)){
        //         $product_date_to = request()->product_date_to;
        //         $args = array_merge($args, ['expired_date' => $product_date_to,]);
        //     }
        // }

        $count = $products->count();
        $products = $products->page();
        // return request();
        return Active::Index(compact('post_type','trash','count','products','partners', 'products_for_combo'));
    }

    public function create()
    {

    }

    public function store(Request $request)
    {

    }

    // public function show($id)
    // {

    // }

    public function edit($id)
    {

    }

    public function update(Request $request, $id)
    {

    }

    public function destroy($id)
    {

    }
}
