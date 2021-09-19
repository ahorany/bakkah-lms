<?php use App\Helpers\Lang; ?>
<table class="table table-striped table-bordered table-hover table-condensed table-total-info my-2">
    <tr>
        <td class="title" style="width: 15%;">{{__('admin.name')}}</td>
        <td class="value" style="width: 30%;">
            <?php
                // echo "<pre>";
                // echo json_encode(json_decode($cartMaster->name??null), JSON_PRETTY_PRINT);
                // echo "</pre>";
            ?>
            <strong>{!!Lang::TransTitle($cartMaster->name??null)!!}</strong></td>
        <td class="title" style="width: 20%;">{{__('admin.email')}}</td>
        <td class="value" style="width: 35%;">{{$cartMaster->email??null}}</td>
    </tr>
    <tr>
        <td class="title">{{__('admin.mobile')}}</td>
        <td class="value">{{$cartMaster->mobile??null}}</td>
        <td class="title">{{__('admin.gender')}}</td>
        <td class="value">{!!Lang::TransTitle($cartMaster->gender??null)!!}</td>
    </tr>
    <tr>
        <td class="title">{{__('admin.job_title')}}</td>
        <td class="value">{{$cartMaster->job_title??null}}</td>
        <td class="title">{{__('admin.company')}}</td>
        <td class="value">{{$cartMaster->company??null}}</td>
        {{-- <td class="value">{{App\Helpers\Lang::TransTitle($cartMaster->userId->company??null)}}</td> --}}
    </tr>
    @if(isset($cartMaster->type_id))
        <tr>
            <td class="title">{{__('admin.country')}}</td>
            {{-- <td class="value">{!!GetValueByLang($cartMaster->country??null)!!}</td> --}}
            <td class="value">{!!Lang::TransTitle($cartMaster->country??null)!!}</td>
            <?php
                // $class = [
                //     370 => 'dark', // B2B
                //     372 => 'info', // Group
                //     373 => 'warning', // RFQ
                //     374 => 'success', // B2C
                //     // 373 => 'danger',
                // ];
            ?>
            <td class="value">Invoice
                {{-- <span class="badge badge-{{$class[$cartMaster->type_id]??null}}">
                    {{$cartMaster->type->trans_name??null}}
                </span> --}}
            </td>
            <td class="value">{{$cartMaster->invoice_number??null}}</td>
        </tr>
        @if($cartMaster->type_id == 374 && isset($cartMaster->username_lms))
            <tr>
                <td class="title">{{__('admin.username')}}</td>
                <td class="value">{{$cartMaster->username_lms??null}}</td>
                <td class="title">{{__('admin.password')}}</td>
                <td class="value">{{$cartMaster->password_lms??null}}</td>
            </tr>
        @endif
        @if($cartMaster->type_id != 374)
            <tr>
                <td class="value" colspan="3">
                    <?php
                        $group = Modules\CRM\Entities\GroupInvoiceMaster::where("master_id", $cartMaster->id)->first();
                        if(!is_null($group)){
                            $organization = Modules\CRM\Entities\Organization::where("id", $group->organization_id)->first();
                        }
                    ?>
                    {!!Lang::TransTitle($organization->title??null)!!}
                    {{-- {{$cartMaster->rfpGroup->organization->en_title??null}} --}}
                </td>
                <?php
                    $class = [
                        357 => 'success', //Paid
                        358 => 'dark', //PO
                        356 => 'info', //Invoice
                        355 => 'warning', //Pending
                        359 => 'danger', //Cancel
                    ];
                ?>
                <td class="value">
                    @if($cartMaster->status_id)
                        <span class="badge badge-{{$class[$cartMaster->status_id]??null}}">
                            {{-- {{$cartMaster->status->trans_name??null}} --}}
                            {!!Lang::TransTitle($cartMaster->status_name??null)!!}
                        </span>
                    @endif
                </td>
            </tr>
        @endif
        <tr class="table-info">
            <td class="title">{{__('admin.sub-total')}}</td>
            <td class="value">{{$cartMaster->total??null}}</td>
            <td class="title">{{__('admin.vat')}}(<span style="color:#dc3545;">{{$cartMaster->vat}}%</span>)</td>
            <td class="value">{{$cartMaster->vat_value}}</td>
        </tr>

        <tr class="table-info">
            <td class="title">{{__('admin.total_after_vat')}}</td>
            <td class="value text-primary">{{$cartMaster->total_after_vat??null}} <small>{!!Lang::TransTitle($cartMaster->coin_name??null)!!}</small></td>

            <td class="title">Payment</td>
            <td class="value text-primary">
                @if(isset($cartMaster->paid_in) && $cartMaster->paid_in>0)
                    <span class="title text-primary d-block">Paid in: <span class="ml-2 paid-value badge {{($cartMaster->coin_id==334)?'badge-success':'badge-warning'}}">
                    {{($cartMaster->paid_in!=0)?$cartMaster->paid_in:0}} <small>{!!Lang::TransTitle($cartMaster->coin_name??null)!!}</small>
                    </span></span>
                    {{-- <small class="text-dark"> at {{$cartMaster->payments_updated_at}}</small> --}}
                @endif
                @if(isset($cartMaster->paid_out) && $cartMaster->paid_out>0)
                <span class="title text-danger d-block pt-1">Paid out: <span class="paid-value badge {{($cartMaster->coin_id==334)?'badge-success':'badge-warning'}}">
                    {{($cartMaster->paid_out!=0)?$cartMaster->paid_out:0}} <small>{!!Lang::TransTitle($cartMaster->coin_name??null)!!}</small>
                    </span></span>
                    {{-- <small class="text-dark"> at {{$cartMaster->payments_updated_at}}</small> --}}
                @endif
            </td>
        </tr>

        <tr class="table-info">
            <td class="title">{{__('admin.date')}}</td>
            <td class="author">{{$cartMaster->registered_at??$cartMaster->created_at}}</td>
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
            <td class="title">Payment {{__('admin.status')}}</td>
            <td class="value">
                @if ($cartMaster->payment_status)
                    <span class="paid-value badge badge-{{$class[$cartMaster->payment_status]??null}}">
                        @if($cartMaster->payment_status==376)
                            Transferred From
                        @elseif ($cartMaster->payment_status==377)
                            Transferred To
                        @else
                            {!!Lang::TransTitle($cartMaster->payment_status_name??null)!!}
                        @endif
                        {{-- {!!Lang::TransTitle($cartMaster->payment_status_name??null)!!} --}}
                    </span>
                @endif
            </td>
        </tr>

        @if($cartMaster->xero_prepayment && $cartMaster->payment_status==68)
            <tr class="table-info">
                <td class="title">Prepayment</td>
                <td class="value text-primary">
                    <small class="paid-value badge badge-warning">{{$cartMaster->xero_prepayment}}</small>
                </td>
                <td class="title">{{__('admin.date')}}</td>
                <td class="value text-primary">
                    <small class="text-gray">{{$cartMaster->xero_prepayment_created_at??null}}</small>
                    {{-- <small class="text-gray">{{date('d-m-Y', strtotime($cartMaster->xero_prepayment_created_at))}}</small> --}}
                </td>
            </tr>
        @endif

    @endif

</table>
