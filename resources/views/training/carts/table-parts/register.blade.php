<?php
    use App\Helpers\Lang;
    // $master_id__field = 'master_id';
    // if($cartMaster->type_id==372){
    //     request()->post_type = 'group_invoices';
    //     $master_id__field = 'group_invoice_id';
    // }
    // $carts = App\Models\Training\Cart::where("$master_id__field", $cartMaster->id)
    $carts = App\Models\Training\Cart::where("master_id", $cartMaster->id)
    ->whereNull('trashed_status')
    ->whereNull('deleted_at')
    ->with(['session'=>function($query){ $query->withTrashed(); },
            'trainingOption'=>function($query){ $query->withTrashed(); },
            'course'=>function($query){ $query->withTrashed(); }
          ])
    ->get();
?>
@if ($cartMaster->type_id != 374)
    <span class="text-bold text-white badge badge-dark mb-2" style="font-size: 13px;">{{$carts->count()}} Trainees</span>
    <div style="max-height: 380px; overflow-y: auto;">
@endif
<ul class="list-unstyled m-1">
    @foreach($carts as $cart)
        <li class="mb-2">
            {{-- {{$cart->id??null}} --}}
            @if ($cartMaster->type_id != 374)
                <span class="d-block">{{$loop->iteration}}) <strong>{{$cart->userId->trans_name??null}} | {{$cart->userId->email??null}}</strong></span>
            @endif
            <span class="text-secondary" style="font-size: 16px;"><span>{{$cart->session->trainingOption->training_name??null}}</span></span>
            <span style="color: #999; font-size: 13px;display: block;">
                {{$cart->session->published_from??null}} - {{$cart->session->published_to??null}}
                <span class="badge badge-info ml-2">SID: {{$cart->session->id??null}}</span>
                <span class="text-primary d-block" style="font-size: 14px;">Course Price: <small>{{NumberFormatWithComma($cart->price-$cart->discount_value)}} {{$cart->coin->en_name??null}}</small></span>
                @if (isset($cart->promo_code))
                    <span class="text-danger d-block" style="font-size: 14px;"><small>{{$cart->promo_code??null}}</small></span>
                @endif

                @foreach($cart->cartFeatures as $cartFeature)
                    <span class="text-success d-block" style="font-size: 14px;">{{App\Helpers\Lang::TransTitle($cartFeature->trainingOptionFeature->feature->title)??null}}: <small>{{NumberFormatWithComma($cartFeature->price)??null}} {{$cartMaster->coin->en_name??null}}</small></span>
                @endforeach
            </span>
            <?php
            $class = [
                63 => 'danger',
                377 => 'danger',
                68 => 'success',
                376 => 'success',
                315 => 'info',
                316 => 'warning',
                317 => 'info',
                332 => 'dark'
            ];
            ?>
            <span class="text-secondary d-block" style="font-size: 13px;">Payment Status: <span class="paid-value badge badge-{{$class[$cart->payment_status]??null}}">
                {{$cart->paymentStatus->trans_name??null}}
            </span></span>
            <span class="text-secondary d-block" style="font-size: 13px;">Registered at: <small>{{$cart->registered_at??$cart->created_at}}</small></span>
            <div>Invoice: <span class="text-secondary">{{$cart->invoice_number??null}}</span></div>
            @if ($cartMaster->type_id != 374)
                <div>{{__('admin.username')}}: <span class="text-secondary">{{$cart->userId->username_lms??null}}</span></div>
                <div>{{__('admin.password')}}: <span class="text-secondary">{{$cart->userId->password_lms??null}}</span></div>
                <span style="width: 50%; display: inline-block; border-bottom: 1px solid #e3e3e3;"></span>
            @endif

            <div>
                @if($cart->xero_invoice && $cart->payment_status==68)
                    <u><span class="text-normal badge badge-secondary">Invoice</span></u><small class=" text-normal ml-2 badge badge-secondary">{{$cart->xero_invoice}}</small> <small class="text-gray">{{$cart->xero_invoice_created_at??null}}</small>
                @endif
            </div>
        </li>

    @endforeach
</ul>
@if ($cartMaster->type_id != 374)
    </div> {{-- <div style="overflow-y:scroll" > --}}
@endif
