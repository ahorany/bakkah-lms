<table class="table table-striped table-bordered table-hover table-condensed table-total-info my-2">
    <tr>
        <td class="title">{{__('admin.name')}}</td>
        <td class="value" style="width:250px;"><strong>{{$cartMaster->userId->trans_name??null}}</strong></td>
        <td class="title" style="width:100px;">{{__('admin.email')}}</td>
        <td class="value">{{$cartMaster->userId->email??null}}</td>
    </tr>
    <tr>
        <td class="title">{{__('admin.mobile')}}</td>
        <td class="value">{{$cartMaster->userId->mobile??null}}</td>
        <td class="title">{{__('admin.gender')}}</td>
        <td class="value">{{$cartMaster->userId->gender->trans_name??null}}</td>
    </tr>
    <tr>
        <td class="title">{{__('admin.job_title')}}</td>
        <td class="value">{{$cartMaster->userId->job_title??null}}</td>
        <td class="title">{{__('admin.company')}}</td>
        {{-- <td class="value">{{$cartMaster->userId->company??null}}</td> --}}
        <td class="value">{{App\Helpers\Lang::TransTitle($cartMaster->userId->company??null)}}</td>
    </tr>
    @if(isset($cartMaster->type_id))
        <tr>
            <td class="title">{{__('admin.country')}}</td>
            <td class="value">{{$cartMaster->userId->countries->trans_name??null}}</td>
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

        @if($cartMaster->type_id == 374 && isset($cartMaster->userId->username_lms))
            <tr>
                <td class="title">{{__('admin.username')}}</td>
                <td class="value">{{$cartMaster->userId->username_lms??null}}</td>
                <td class="title">{{__('admin.password')}}</td>
                <td class="value">{{$cartMaster->userId->password_lms??null}}</td>
            </tr>
        @endif

        @if($cartMaster->type_id != 374)

            <tr>
                <td class="value" colspan="3">{{$cartMaster->rfpGroup->organization->en_title??null}}</td>
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
                            {{$cartMaster->status->trans_name??null}}
                        </span>
                    @endif
                </td>
            </tr>
        @endif
    @endif
    <tr>
        <td class="title">{{__('admin.sub-total')}}</td>
        <td class="value">{{$cartMaster->total??null}}</td>
        <td class="title">{{__('admin.vat')}}(<span style="color:#dc3545;">{{$cartMaster->vat}}%</span>)</td>
        <td class="value">{{$cartMaster->vat_value}}</td>
    </tr>
    <tr>
        <td class="title">{{__('admin.total_after_vat')}}</td>
        <td class="value">{{$cartMaster->total_after_vat??null}}</td>
        <td class="title">{{__('admin.amount')}}</td>
        <td class="value text-primary">
            @if(isset($cartMaster->payment->paid_in))
                <span class="paid-value badge {{($cartMaster->coin_id==334)?'badge-info':'badge-warning'}}">
                {{($cartMaster->payment->paid_in!=0)?$cartMaster->payment->paid_in:$cartMaster->payment->paid_out}} <small>{{$cartMaster->coin->trans_name??null}}</small>
                </span><br>
            @endif
        </td>
    </tr>
    <tr>
        <td class="title">{{__('admin.date')}}</td>
        <td class="author">{{$cartMaster->registered_at}}</td>
        <?php
            $class = [
                63 => 'danger',
                68 => 'success',
                376 => 'success',
                315 => 'info',
                316 => 'warning',
                317 => 'info',
                332 => 'dark'
            ];
        ?>
        <td class="title">Payment {{__('admin.status')}}</td>
        <td class="title">
            @if ($cartMaster->payment_status)
                <span class="badge badge-{{$class[$cartMaster->payment_status]??null}}">
                    {{$cartMaster->paymentStatus->trans_name??null}}
                </span>
            @endif
        </td>
    </tr>

</table>
