<?php use App\Helpers\Lang; ?>
@extends(ADMIN.'.general.index')

@section('table')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" rel="stylesheet">

<div class="card card-default">

@if($session->trainingOption->constant_id!=13)
<style>
.date_from, .date_to, .session_start_time {
    display: none;
}
</style>
@endif
<div class="card-body">
    <div class="container-fluid">
        <div class="row">
            {!!Builder::Input('training_option_id', 'training_option_id', $session->trainingOption->training_name??null, ['col'=>'col-md-9', 'attr'=>'readonly'])!!}

            {!!Builder::Input('duration', 'duration', $session->duration, [ 'col'=>'col-md-1', 'attr'=>'readonly'])!!}
            {!!Builder::Input('empty', 'empty', $session->durationType->trans_name, [ 'col'=>'col-md-1', 'attr'=>'readonly'])!!}

            {!!Builder::Input('vat', 'vat', $session->vat, ['attr'=>'readonly', 'col'=>'col-md-1'])!!}
            {{-- {!!Builder::Input('session_start_time', 'session_start_time', $session->session_time, [ 'col'=>'col-md-4', 'attr'=>'readonly'])!!} --}}

            {!!Builder::Input('date_from', 'date_from', $session->date_from, ['col'=>'col-md-2', 'attr'=>'readonly'])!!}
            {!!Builder::Input('date_to', 'date_to', $session->date_to, [ 'col'=>'col-md-2', 'attr'=>'readonly'])!!}

            {!!Builder::Input('SID', 'SID', $session->id, [ 'col'=>'col-md-1', 'attr'=>'readonly'])!!}

                <form action="{{ route('training.sessions.importzoom') }}" method="POST" enctype="multipart/form-data" class="col-md-5">
                    @csrf
                    <div class="row">
                        {!!Builder::File('file', 'file', null, ['col'=>'col-md-8'])!!}
                        {!!Builder::Submit('import_zoom_meeting', 'import_zoom_meeting', 'btn-success btn-imp', null, [
                            'icon'=>'far fa-file-excel',
                            'col'=>'col-md-4',
                        ])!!}
                        {!!Builder::Hidden('session_id', $session->id)!!}
                    </div>
                </form>

        </div>

    </div>
    </div>
</div>

<div class="card">
    {{-- Approved --}}
    {{-- @if($session->attendants_status_id != "429" && auth()->id() != 5074) --}}
    @if(($session->attendants_status_id != "429" && auth()->id() != 5074) || (auth()->user()->id==2) || (auth()->user()->id==6174))
        <div class="card-header">
            {!! Builder::Submit('save', 'save', $class='btn-primary btn-sm float-right', 'save') !!}
            {!! Builder::Submit('approve', 'approve', $class='btn-success btn-sm float-right', 'save') !!}



            {{-- Online & ClassRoom --}}
            @if($session->trainingOption->constant_id==13 || $session->trainingOption->constant_id==383)
                {{-- Abdullah, Hani, Tamer, Tariq --}}
                @if(auth()->user()->id==1 || auth()->user()->id==2 || auth()->user()->id==5074 || auth()->user()->id==7085 )
                    {!! Builder::Submit('xero_invoices_run', 'xero_invoices_run', $class='btn-warning btn-sm float-left', 'fa-solid fa-file-import') !!}
                @endif
            @endif
        </div>

        {{-- Done --}}
      @elseif($session->attendants_status_id != "430" && auth()->id() == 5074)
        <div class="card-header">
            {!! Builder::Submit('done', 'done', $class='btn-success btn-sm float-right', 'save') !!}
        </div>
    @endif

    <div class="card-body table-responsive p-0">

        <table class="table table-hover table-condensed">
            <thead>
            <tr>
                <th class="">{{__('admin.index')}}</th>
                <th class="">{{__('admin.master_invoice')}}</th>
                <th class="">{{__('admin.name')}}</th>
                <th class="">{{__('admin.attend_type')}}</th>
                @for($i=1;$i<=$session->duration;$i++)
                    <th class="">Day #{{$i}}</th>
                @endfor
                <th style="width: 100px;">%</th>
                <th style="width: 150px;">Exam Voucher Code</th>
                <th class="">Notes</th>
            </tr>
            </thead>
            <tbody>

            @foreach($carts as $cart)
                    <tr data-id="{{$cart->id}}">
                        <td class="align-middle">
                            <span class="td-title">{{$loop->iteration}}</span>
                        </td>
                        <td class="align-middle text-center">
                            <small class="text-gray">MID: {{$cart->cartMaster->id}}</small>
                            <span class="td-title d-block"><small class="badge badge-dark" style="font-weight: normal;">{{$cart->cartMaster->invoice_number}}</small></span>
                        </td>
                        <td>
                            <span>
                                {{$cart->userId->trans_name}}<br>
                                <small class="text-gray">{{$cart->userId->email}}</small>
                                <small class="badge badge-info" style="font-weight: normal;">{{$cart->invoice_number}}</small>
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
                                 {{-- justify-content-between --}}
                                <div class="d-flex">
                                    <div>
                                        <span class=" text-normal paid-value badge badge-{{$class[$cart->payment_status]??null}}" style="max-width: fit-content;">
                                        {{$cart->paymentStatus->trans_name??null}}
                                        </span>
                                    </div>
                                    <div>
                                        @if($cart->total_after_vat-$cart->refund_value_after_vat!=0)
                                        <span class=" text-normal ml-2 paid-value badge {{($cart->coin_id==334)?'badge-success':'badge-warning'}}">
                                            {{$cart->total_after_vat-$cart->refund_value_after_vat}} <small>{!!Lang::TransTitle($cart->coin->name??null)!!}</small>
                                            </span>
                                            {{-- <span class="title text-primary d-block"></span> --}}
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    @if($cart->cartMaster->xero_prepayment)
                                        <u><span class="text-normal badge badge-warning">Prepayment</span></u><small class=" text-normal ml-2 badge badge-warning">{{$cart->cartMaster->xero_prepayment}}</small> <small class="text-gray">{{date('d-m-Y', strtotime($cart->cartMaster->xero_prepayment_created_at))}}</small>
                                    @endif
                                </div>
                                <div>
                                    @if($cart->xero_invoice)
                                        <u><span class="text-normal badge badge-secondary">Invoice</span></u><small class=" text-normal ml-2 badge badge-secondary">{{$cart->xero_invoice}}</small> <small class="text-gray">{{date('d-m-Y', strtotime($cart->xero_invoice_created_at))}}</small>
                                    @endif
                                </div>
                            </span>
                        </td>
                        <td>
                            <select name="attend_type_id-{{$cart->id}}" class="form-control attend_type_id"  data-cart_id="{{$cart->id}}">
                                <option value="-1">{{__('admin.choose_value')}}</option>
                                @foreach($attend_types as $attend_type)
                                    <option value="{{$attend_type->id}}" {{($cart->attend_type_id==$attend_type->id)?'selected="selected"':''}}>{{$attend_type->trans_name??null}}</option>
                                @endforeach
                            </select>
                        </td>

                        <?php $attendants = $cart->attendants()->get();
                        // dump($attendants->where('attend_day', $i)->count());
                        ?>
                        @for($i=1; $i<=$session->duration; $i++)
                            <td>
                                <div class="custom-control custom-checkbox">
                                    <input {{$attendants->where('attend_day', $i)->count()==1?'checked=""':''}} type="checkbox" class="custom-control-input attendance" id="customCheck-{{$cart->id}}-{{$i}}" data-cart_id="{{$cart->id}}" data-index="{{$i}}">
                                    <label class="custom-control-label" for="customCheck-{{$cart->id}}-{{$i}}">&nbsp;</label>
                                </div>
                                {{-- <span class="light">
                                    <input {{$attendants->where('attend_day', $i)->count()==1?'checked=""':''}} type="checkbox" class="attendance" data-cart_id="{{$cart->id}}" data-index="{{$i}}">
                                </span> --}}
                            </td>
                        @endfor
                        <td>
                            <?php
                                $duration = $session->duration!=0?$session->duration:1;
                                // dump($cart->attendance_count);
                                $progress = ($cart->attendance_count / $duration) * 100;
                                $cls = ($progress>=70)?'bg-success':'bg-danger';
                                $progress = round($progress, 0);
                            ?>
                            <input type="hidden" name="attendance_count-{{$cart->id}}" value="{{$cart->attendance_count}}">
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped {{$cls}}" role="progressbar" style="width: {{$progress}}%" aria-valuenow="{{$progress}}" aria-valuemin="0" aria-valuemax="100">{{$progress}}%</div>
                            </div>
                        </td>
                        <td>
                            <input class="form-control exam_voucher_code" data-cart_id="{{$cart->id}}" name="exam_voucher_code-{{$cart->id}}" value="{{$cart->exam_voucher_code??null}}">
                        </td>
                        <td>
                            <textarea class="form-control notes" data-cart_id="{{$cart->id}}" name="notes-{{$cart->id}}" cols="5" rows="1">{{$cart->notes()->first()->comment??null}}</textarea>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <input type="hidden" name="session_id" value="{{$session->id}}">

        </table>
    </div>
</div>
@endsection
@push('vue')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.js.map"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- /.card-body -->
<script>
    $(function(){
        var arrayRemoved = [];
        var arrayInserted = [];
        var duration = $('[name="duration"]').val()!=0?$('[name="duration"]').val():1;
        $('.attendance').change(function(){

            var cart_id = $(this).data('cart_id');
            var index = $(this).data('index');
            var attendance_count = $('[name="attendance_count-'+cart_id+'"]').val();

            if($(this).is(':checked')) {
                arrayInserted.push({cart_id: cart_id, index: index});
                attendance_count = parseFloat(attendance_count) + 1;
                // toastr.success('Attendance Checked Successfully', 'Checked Successfully!')
                // console.log('Inserted');
                // console.log(arrayInserted);
            }
            else{
                arrayRemoved.push({cart_id: cart_id, index: index});
                attendance_count = parseFloat(attendance_count) - 1;
                // toastr.error('Attendance Unchecked Successfully', 'Unchecked Successfully!')
                // console.log('Removed');
                // console.log(arrayRemoved);
            }
            $('[name="attendance_count-'+cart_id+'"]').val(attendance_count)

            // alert(attendance_count + '===>' + duration);
            var progress = (parseFloat(attendance_count) / parseFloat(duration)) * 100;
            $('tr[data-id="'+cart_id+'"] .progress-bar').html(progress.toFixed(0) + '%');
            $('tr[data-id="'+cart_id+'"] .progress-bar').attr('aria-valuenow', 'progress');

            if(progress>=70){
                $('tr[data-id="'+cart_id+'"] .progress-bar').removeClass('bg-danger');
                $('tr[data-id="'+cart_id+'"] .progress-bar').addClass('bg-success');
            }
            else{
                $('tr[data-id="'+cart_id+'"] .progress-bar').removeClass('btn-success');
                $('tr[data-id="'+cart_id+'"] .progress-bar').addClass('bg-danger');
            }
            $('tr[data-id="'+cart_id+'"] .progress-bar').css({'width': progress.toFixed(0) + '%'});
        });

        var arrayNotes = [];
        $('.exam_voucher_code, .notes, .attend_type_id').change(function(){
            // alert('ssss');
            var cart_id = $(this).data('cart_id');
            var attend_type_id = $('select[name="attend_type_id-'+cart_id+'"]').find(':selected').val();
            var exam_voucher_code = $('[name="exam_voucher_code-'+cart_id+'"]').val();
            var notes = $('[name="notes-'+cart_id+'"]').val();
            arrayNotes.push({cart_id: cart_id, attend_type_id: attend_type_id, exam_voucher_code: exam_voucher_code, notes: notes});
            // console.log(arrayNotes);
        });

        $('[name="save"], [name="approve"], [name="done"], [name="xero_invoices_run"]').click(function(e){

            e.preventDefault();

            let action = "save";
            let bool = true;


            if( $(this).attr('name') == "approve") {
                action = "approve";
                bool = window.confirm("Are you sure about the process of changing to approved !!");
            }
             else if( $(this).attr('name') == "done"){
                action = "done";
            }
             else if( $(this).attr('name') == "xero_invoices_run"){
                action = "xero_invoices_run";
                // console.log('aaaaaaaaaaaaaaaaaaaaa');
            }
            if(bool){

                $.ajax({
                type:'get',
                url:'{{route("training.sessions.attendance.store")}}',
                data:{
                    session_id: '{{$session->id}}',
                    arrayInserted:arrayInserted,
                    arrayRemoved:arrayRemoved,
                    arrayNotes:arrayNotes,
                    action : action
                },
                success:function (data){
                    var session_id = '{{$session->id}}';
                    var training_option_id = '{{$session->trainingOption->constant_id}}';
                    // console.log(session_id);
                    // console.log(training_option_id);
                    // return training_option_id;
                    // console.log(data['msg']);
                    if(data['msg']=='xero_invoices_run'){
                        if (session_id && training_option_id) {
                            //Online or ClassRoom
                            if(training_option_id == 13 || training_option_id == 383){
                                // var url = 'www.google.com';
                                var url = '/xero/authorization?post_type=invoices&session=' + session_id + '&training_option_id=' + training_option_id;
                                window.open(url, '_blank').focus();
                                // window.location = '/xero/authorization?post_type=invoices&session=' + session_id + '&training_option_id=' + training_option_id;
                            }
                        }else{
                            toastr.error('There is an error, maybe no session ID or Delivery Option!', 'Error!')
                        }
                        return;
                    }
                    // console.log(data);
                    arrayRemoved = [];
                    arrayInserted = [];
                    arrayNotes = [];
                    if( action == "done" || action == "approve") {
                        $('.card-header').remove();
                    }
                    toastr.success('Attendance Checked Successfully', 'Checked Successfully!');
                    //
                    // if(action == "approve"){
                    //     location.reload();
                    // }
                },
                error:function (e){
                    console.log(e);
                    toastr.error('Attendance Unchecked Successfully', 'Unchecked Successfully!')
                }
            });
            }

        });

    });
</script>
@endpush
