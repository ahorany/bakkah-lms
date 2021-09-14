<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Helpers\Active;
use App\Models\Training\CartMaster;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class XeroController extends Controller
{
    public function __construct()
    {
        Active::$namespace = 'training';
        Active::$folder = 'prepayments';
    }

    private function GetPaginator($items)
    {
        $total = count($items); // total count of the set, this is necessary so the paginator will know the total pages to display
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = PAGINATE; // how many items you want to display per page?
        $offset = ($currentPage - 1) * $perPage; // get the offset, how many items need to be "skipped" on this page
        $items = array_slice($items, $offset, $perPage); // the array that we actually pass to the paginator is sliced

        return new LengthAwarePaginator($items, $total, $perPage, $currentPage, [
            'path' => request()->url(),
            'query' => request()->query()
        ]);
    }

    public function totalCount(){

        $totalCount  = DB::select(DB::raw("SELECT count(cart_masters.id) as totalCount FROM cart_masters Where cart_masters.deleted_at is null and cart_masters.user_id is not null and cart_masters.id in( SELECT carts.master_id FROM carts Where carts.deleted_at is null and carts.trashed_status is null )"));

        return $totalCount[0]->totalCount??0;

    }

    public function index(){

        $post_type = 'prepayments';
        $trash = GetTrash();

        $totalCount = $this->totalCount();

        $cartMasters_get = CartMaster::whereNull('xero_prepayment')
            ->whereNull('xero_prepayment_created_at')
            ->where('type_id', 374)
            ->whereNull('wp_migrate')
            ->whereNull('trashed_status')
            ->whereHas('payment', function (Builder $query){
                $query->where('payment_status', 68);
            })
            ->whereHas('carts', function (Builder $query){
                $query->whereNull('trashed_status')
                      ->where('payment_status', 68);
            })
            ->with([
                'userId.gender:id,name',
                'userId.countries:id,name',
                'type:id,name',
                'coin:id,name',
                'rfpGroup.organization:id,title',
                'status:id,name',
                'paymentStatus:id,name',
                'payment', 'carts'=>function($query){
                $query->whereNull('trashed_status')
                      ->with(['trainingOption'=>function($query){
                        }, 'course'=>function($query){
                        }, 'session'=>function($query){
                        }, 'cartFeatures.trainingOptionFeature.feature'=>function($query){
                        }]);
            }])
            // ->whereNotIn('id', [4300,4327])
            // ->whereNotIn('id', [4026])
            ->orderBy('id', 'asc')
            // ->where('id', 10711)  //4059
            // ->whereIn('id', [11883,12100])
            // ->take(2)
            ->get();
            // dd($cartMasters);
        // } //if

        // dd($cartMasters_get->toSql());
        // dd($cartMasters);

        $cartMasters_arr = $cartMasters_get->toArray();
        // dd($cartMasters);

        $paginator = $this->GetPaginator($cartMasters_arr);
        $cartMasters = $paginator->items();
        $count = $paginator->total();
        // $cartMasters = $cartMasters->page();

        $cartMasters = json_decode(json_encode($cartMasters), FALSE);

        // dd($cartMasters);

        return Active::Index(compact('cartMasters', 'totalCount', 'count', 'post_type', 'trash', 'paginator'));
    }

}
