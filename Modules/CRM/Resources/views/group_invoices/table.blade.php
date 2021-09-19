<div class="card">
    <div class="card-header">
        {!!Builder::SetNameSpace('')!!}
        {!!Builder::BtnGroupTable()!!}
        {!!Builder::TableAllPosts($count, $cartMasters->count())!!}
    </div>

    <div class="card-body table-responsive p-0">
        <table class="table table-hover table-condensed">
            <thead>
            <tr>
                <th class="">{{__('admin.index')}}</th>
                <th width="15%" class="">{{__('admin.title')}}</th>
                <th class="">{{__('admin.course_name')}}</th>
                <th class="">{{__('admin.session_id')}}</th>
                <th class="">{{__('invoice_number')}}</th>
                <th class="">{{__('admin.status')}}</th>
                <th class="">{{__('admin.payment_status')}}</th>
                <th width="9%" class="">{{__('admin.follow_up_date')}}</th>
                <th class="d-none d-sm-table-cell">{{__('admin.owner_user')}}</th>
                <th class="d-none d-sm-table-cell user-td">{{__('admin.last_update')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($cartMasters as $cartMaster)
                <tr data-id="{{$cartMaster->id}}">
                    <td>
                        <span>{{$loop->iteration}}</span>
                    </td>
                    <td>
                        <span style="display: block;">{{$cartMaster->rfpGroup->organization->en_title??null}}</span>
                        {!!Builder::BtnGroupRows($cartMaster->rfpGroup->organization->en_title??null, $cartMaster->id, [], [
                           'invoice'=>$cartMaster->id,
                        ])!!}
                    </td>
                    <td>
                        <span style="display: block;">{{$cartMaster->rfpGroup->session->trainingOption->training_name??null}}</span>
                    </td>
                    <td>
                        <span style="display: block;">{{$cartMaster->rfpGroup->session->published_from??null}} - {{$cartMaster->rfpGroup->session->published_to??null}}</span>
                    </td>
                    <td>
                        <span>{{$cartMaster->invoice_number??null}}</span>
                    </td>
                    <td class="value">
                        <?php
                            $class = [
                                357 => 'success', //Paid
                                358 => 'dark', //PO
                                356 => 'info', //Invoice
                                355 => 'warning', //Pending
                                359 => 'danger', //Cancel
                            ];
                        ?>
                        @if($cartMaster->status_id)
                            <span class="badge badge-{{$class[$cartMaster->status_id]??null}}">
                                {{$cartMaster->status->trans_name??null}}
                            </span>
                        @endif
                    </td>
                    <td class="d-none d-sm-table-cell">
                        <?php
                            $class = [
                                63 => 'danger',
                                68 => 'success',
                                315 => 'info',
                                316 => 'warning',
                                317 => 'info',
                                332 => 'dark'
                            ];
                        ?>
                        @if($cartMaster->payment_status)
                            <span class="badge badge-{{$class[$cartMaster->payment_status]??null}}">
                                {{$cartMaster->paymentStatus->trans_name??null}}
                            </span>
                        @endif
                    </td>
                    <td class="value">
                        <span>
                          {{$cartMaster->rfpGroup->follow_up_date??null}}
                        </span>
                    </td>
                    <td class="value">
                        <span>
                           {{$cartMaster->rfpGroup->userId->trans_name??null}}
                        </span>
                    </td>
                    <td class="d-none d-sm-table-cell">
                        <span class="author">
                          {!!$cartMaster->rfpGroup->published_at??null!!}<br>
                        </span>
                      </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<!-- /.card-body -->
{{-- {{ $cartMasters->render() }} --}}
{{ $cartMasters->appends(['post_type' => request()->post_type??null, 'type_id' => request()->type_id??null])->render() }}
