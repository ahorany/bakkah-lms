<?php

namespace App\Exports;

use App\Models\Training\Cart;
use App\Helpers\Models\Training\CartHelper;
use App\Models\Training\CartMaster;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Database\Eloquent\Builder;
use App\User;
use Illuminate\Pagination\LengthAwarePaginator;

class RegistrationExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($withFeatures)
    {
        $this->withFeatures = $withFeatures;
    }

    private function GetPaginator($items)
    {
        $total = count($items); // total count of the set, this is necessary so the paginator will know the total pages to display
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 1000; //PAGINATE; // how many items you want to display per page?
        $offset = ($currentPage - 1) * $perPage; // get the offset, how many items need to be "skipped" on this page
        $items = array_slice($items, $offset, $perPage); // the array that we actually pass to the paginator is sliced

        return new LengthAwarePaginator($items, $total, $perPage, $currentPage, [
            'path' => request()->url(),
            'query' => request()->query()
        ]);
    }

    public function view(): View
    {
        // dd($this->withFeatures);
        // $withFeatures = request()->withFeatures??'No';
        $withFeatures = $this->withFeatures??null;

        $CartHelper = new CartHelper();
        $cartMasters = $CartHelper->Query(true, false);

        $paginator = $this->GetPaginator($cartMasters);
        $cartMasters = $paginator->items();

        // dd($cartMasters);

        $post_type = 'carts';
        $count = 0;
        $trash = null;
        return view('training.carts.export', compact('cartMasters', 'post_type','count', 'trash', 'withFeatures'));

//         // $users = User::where('email', 'not like', '%@bakkah.net.sa%')->get();
// //         $users = CartMaster::where('type_id' ,374)->with('UserId')->get();
// // // dd($users);
// //         $post_type = 'users';
// //         $count = 0;
// //         $trash = null;
// //         return view('training.carts.users_export', compact('users', 'post_type','count', 'trash'));

//         // ================================================================
//         $carts = Cart::with(['course:id,title', 'payment', 'userId']);

//         // $carts = $carts->whereHas('trainingOption', function (Builder $query) {
//         //     $query->where('constant_id', 11);
//         // });
//         // $carts = $carts->doesntHave('payment');
//         // $carts = $carts->whereHas('payment', function (Builder $query) {
//         //     $query->whereIn('payment_status', [68, 317, 332]);
//         // });

//         // $carts = $carts->whereHas('payment', function (Builder $query) {
//         //     $query->whereIn('payment_status', [68, 317, 332, 315]);
//         // });
//         // $carts = $carts->whereHas('trainingOption.session', function(Builder $query){
//         //         $query->whereMonth('date_from', '=', 10);
//         //         $query->orWhereMonth('date_from', '=', 11);
//         // });


//         // $carts = $carts->whereHas('trainingOption', function (Builder $query) {
//         //     $query->where('constant_id', 11);
//         // });
//         // $carts = $carts->where('total_after_vat', 0)
//         //                 ->whereDate('registered_at', '>=', '2020-10-01');


//         // For Tamer
//         // $carts = Cart::with(['course:id,title', 'userId']);
//         // $carts = $carts->whereHas('payment', function (Builder $query) {
//         //     $query->whereIn('payment_status', [68]);///, 317, 332, 315
//         // })
//         // ->whereHas('session', function(Builder $query){
//         //     $query->whereRaw('MONTH(date_from) in (?, ?)', [10, 11]);
//         // })
//         // ->with(['session'=>function($query){
//         //     $query->whereRaw('MONTH(date_from) in (?, ?)', [10, 11]);
//         // }])
//         // ->with(['trainingOption', 'session'])
//         // ->get();

//         //For AbuShanab
//         // $carts = Cart::with(['course:id,title', 'userId']);
//         // $carts = $carts->whereHas('payment', function (Builder $query) {
//         //     $query->where('payment_status', 63);
//         // })
//         // ->with('trainingOption.session')
//         // ->get();


//         $carts = Cart::whereNotNull('id')
//         // ->whereIn('post_year', [2019,2020,2021])
//         // // ->where('wp_city', 'not like', '%%b2b%%');
//         // ->where(function ($query) {
//         //     $query->where('wp_city', 'not like', '%%b2b%%');
//         //     $query->orWhereNull('wp_city');
//         // });
//             ->whereNull('trashed_status')
//             ->whereNull('deleted_at')
//             ->whereHas('userId', function (Builder $query) {
//                 if(request()->has('user_search') && !is_null(request()->user_search)){
//                     $query->where('name', 'like', '%'.request()->user_search.'%')
//                         ->orWhere('email', 'like', '%'.request()->user_search.'%')
//                         ->orWhere('mobile', 'like', '%'.request()->user_search.'%')
//                     ;
//                 }
//             });

//         if( isset(request()->course_id) && request()->course_id!=-1) {
//             $carts = $carts->where('course_id', request()->course_id);
//         }

//         if( isset(request()->session_id) && request()->session_id!=-1) {
//             $carts = $carts->where('session_id', request()->session_id);
//         }

//         if( isset(request()->training_option_id) && request()->training_option_id!=-1) {
//             $carts = $carts->whereHas('trainingOption', function (Builder $query) {
//                 $query->where('constant_id', request()->training_option_id);
//             });
//         }

//         if(isset(request()->invoice_number) && !is_null(request()->invoice_number)) {
//             $carts = $carts->where('invoice_number', 'like', '%'.request()->invoice_number.'%');
//         }

//         if(isset(request()->date_from) && !is_null(request()->date_from)) {
//             $carts = $carts->whereDate('registered_at', '>=', request()->date_from);
//         }

//         if(isset(request()->date_to) && !is_null(request()->date_to)) {
//             $carts = $carts->whereDate('registered_at', '<=', request()->date_to);
//         }

//         if(isset(request()->session_from) && !is_null(request()->session_from)) {
//             $carts = $carts->whereHas('session', function (Builder $query) {
//                 $query->whereDate('date_from', '>=', request()->session_from);
//             });
//         }

//         if(isset(request()->session_to) && !is_null(request()->session_to)) {
//             $carts = $carts->whereHas('session', function (Builder $query) {
//                 $query->whereDate('date_from', '<=', request()->session_to);
//             });
//         }

//         if(isset(request()->payment_status) && request()->payment_status!=-1) {
//             if(request()->payment_status==332){
//                 $carts = $carts->doesntHave('payment');
//             }
//             else{
//                 $carts = $carts->whereHas('payment', function (Builder $query) {
//                     $query->where('payment_status', request()->payment_status);
//                 });
//             }
//         }

//         if(isset(request()->country_id) && request()->country_id!=-1) {
//             $carts = $carts->whereHas('userId.countries', function (Builder $query){
//                 $query->where('country_id', request()->country_id);
//             });
//         }

//         if(request()->has('category_id') && request()->category_id!=-1){
//             $carts = $carts->whereHas('course.postMorphs', function (Builder $query){
//                 $query->where('constant_id', request()->category_id);
//             });
//         }

        // $carts = $carts->with(['course:id,title,certificate_type_id', 'payment', 'userId.countries'])->orderBy("id", "desc")->take(1000)->get();
        // // ->skip(0)->take(2000)


        // $post_type = 'carts';
        // $count = 0;
        // $trash = null;
        // return view('training.carts.export', compact('cartMasters', 'post_type','count', 'trash'));

        // =============================================
    }
}
