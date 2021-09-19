<style>
    .price-card .title {
        width: 150px;
    }
    span.value {
        font-weight: bold;
        font-size: 12px;
        color: #666;
    }
    .table-total-info {
        font-size: 14px;
    }
    .table-total-info td {
        padding-left: 1rem !important;
    }
    .paid-value {
        font-size: 13px; font-weight: normal;
    }
    #msg{
        display: none;
        margin: 0 !important;
        padding: .25rem .5rem !important;
        font-size: .875rem !important;
    }
    .to_enable{
        border-radius: 30%;
    }
</style>
{{-- {{Builder::SetTrash($trash)}} --}}
{{Builder::SetPostType('evaluation')}}
{{Builder::SetFolder('evaluations')}}
{{Builder::SetObject('evaluation')}}
{{Builder::SetNameSpace('training.')}}
<style>
    .card-columns {
        -webkit-column-count: 2;
        -moz-column-count: 2;
        column-count: 2;
    }
</style>

<form method="post" id="submit-evaluation-form" action="{{route('evaluation.sending')}}">
    @csrf
    {!! Builder::Hidden('collectortoken', $collectortoken??null) !!}

    <div class="card">
        <div class="card-header d-fex justify-content-between">
            {!! Builder::Submit('send_evaluation', 'send_evaluation', 'btn-success', 'paper-plane') !!}
            <div class="d-inline mx-3 badge text-secondary" id="numbers_count"></div>
            <div id="msg" class="alert "></div>

            {{-- {!!Builder::BtnGroupTable(false)!!} --}}
            {!!Builder::TableAllPosts($count, $carts->count())!!}
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-condensed" style="font-size: 14px;">
                <thead>
                <tr>
                    <th class=""><input type="checkbox" id="selectall"/> {{__('admin.check')}}</th>
                    <th class="">CID</th>
                    <th class="" style="width:400px;">{{__('admin.user')}}</th>
                    <th class="" style="width:400px;">{{__('admin.title')}}</th>
                    <th class="" style="width:300px;">{{__('admin.table')}}</th>
                    <th class="" style="">{{__('admin.Evaluation')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($carts as $post)
                    <tr data-id="{{$post->id}}">
                        <?php
                            $dis = '';
                            $chk = '';
                            if(!is_null($post->evaluation_sent_at)){
                                $dis = 'disabled';
                                $chk = 'hide';
                            }
                        ?>
                        <td><input type="checkbox" class="chk {{$chk}}" name="check[]" value="{{$post->id}}" {{$dis}}/>
                            @if(!is_null($post->evaluation_sent_at))
                                <span class="to_enable btn btn-sm btn-success btn-sm" id="{{$post->id}}"><i class="fa fa-times"></i></span>
                            @endif
                        </td>
                        <td>
                            <span class="td-title">{{$post->id}}</span>
                        </td>
                        <td>
                            <ul>
                                <li><span class="text-secondary">{{__('admin.name')}}:</span> {{$post->userId->trans_name??null}}</li>
                                <li><span class="text-secondary">{{__('admin.email')}}:</span> {{$post->userId->email??null}}</li>
                                <li><span class="text-secondary">{{__('admin.mobile')}}:</span> {{$post->userId->mobile??null}}</li>
                                <li><span class="text-secondary">{{__('admin.invoice number')}}:</span> {{$post->invoice_number??null}}</li>
                            </ul>
                        </td>
                        <td>
                            <span style="display: block;">{{$post->trainingOption->training_name??null}}</span>
                            <?php $arge = 'Empty'; ?>
                            <span style="color: #999; font-size: 12px;">
                                {{$post->session->published_from??null}} - {{$post->session->published_to??null}}<br>
                                SID: {{$post->session->id??null}}<br>
                            </span>
                        </td>
                        {{-- Price --}}
                        <td class="price-card">
                            <table class="table table-striped table-bordered table-hover table-condensed table-total-info">

                                <tr>
                                    <td class="title">{{__('admin.paid')}}</td>
                                    <td class="value">
                                            <span class="paid-value badge badge-info">
                                                {{($post->total_after_vat!=0)?$post->total_after_vat:0}}
                                            </span><br>
                                        {{-- @if(isset($post->payment->paid_in))
                                            <span class="paid-value badge badge-info">
                                                {{($post->payment->paid_in!=0)?$post->payment->paid_in:$post->payment->paid_out}}
                                            </span><br>
                                        @endif --}}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="title">{{__('admin.status')}}</td>
                                    <td class="value">
                                            <span class="badge {{($post->payment_status==68 || $post->payment_status==376)?'badge-success':'badge-danger'}}">
                                                {{$post->paymentStatus->trans_name??null}}
                                            </span><br>
                                        {{-- @if(isset($post->payment->paid_in))
                                            <span class="badge {{($post->payment->payment_status==68)?'badge-success':'badge-danger'}}">
                                                {{$post->payment->paymentStatus->trans_name??null}}
                                            </span><br>
                                        @endif --}}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="title">{{__('admin.date')}}</td>
                                    <td class="author">{{$post->registered_at}}</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            @if(isset($post->payment->payment_status) && ($post->payment->payment_status==332 || $post->payment->payment_status==317 || $post->payment->payment_status==315 || $post->payment->payment_status==68 || $post->payment->payment_status==376 ))
                                @if(auth()->user()->id==1 || auth()->user()->id==2 || auth()->user()->id==3 || auth()->user()->id==3178 ||
                                auth()->user()->id==5337 || auth()->user()->id==6174 || auth()->user()->id==13094)
                                @endif
                                @if($post->certificate_sent_at)
                                    <small class="text-success" style="display: block;">Evaluation was sent before at {{$post->evaluation_sent_at}}</small>
                                @endif

                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</form>
<!-- /.card-body -->
{{ $carts->render() }}
<script>
    jQuery(function (){
        jQuery('.page-link').click(function (){
            var btnName = jQuery(this);
            var page = jQuery(this).data('page');
            AjaxSearch(btnName, page);
            return false;
        });

        // ===== Select All =======
        $('#selectall').click(function(e){
            var table= $(e.target).closest('table');
            $('td input:checkbox',table).prop('checked',this.checked);
            check();
        });

        // ===== Select One check =======
        $('input:checkbox').click(function () {
            // alert('yes');
            check();
        });
        // ================================

        //  retrieve selcted checked items
        function check() {
            var checkboxes = document.getElementsByName('check[]');
            var numbers_vals = "";
            var n_counter = 0;

            for (var i=0, n=checkboxes.length;i<n;i++)
            {
                if (checkboxes[i].checked)
                {
                    numbers_vals += ","+checkboxes[i].value;
                    n_counter++;
                }
            }
            if (numbers_vals) numbers_vals = numbers_vals.substring(1);
            $('#numbers_count').html('(<span style="color: #fb4400;">' + n_counter + '</span>) selected');

            $('#get_ids').val(numbers_vals);
        }

        // ==================================
        $('#submit-evaluation-form').on('submit', function(event){
            $('#msg').css("display", "none");
            var btnSubmitHtml = $('[name="send_evaluation"]').html();
            // '<i class="fa fa-paper-plane"></i> Send Evaluation'
            event.preventDefault();
            $.ajax({
                url:$('#submit-evaluation-form').attr('action'),
                type:'post',
                data: $(this).serialize(),
                beforeSend:function(){
                    $('[name="send_evaluation"]').attr('disabled', 'disabled');
                    $('[name="send_evaluation"]').html("Sending...");
                },
                success:function(data){
                    $('[name="send_evaluation"]').removeAttr('disabled');
                    $('[name="send_evaluation"]').html(btnSubmitHtml);

                    var msg_en = '';
                    if(data == 'success'){
                        msg_en = '<i class="fa fa-check" aria-hidden="true"></i> The evaluations have been put in queue for sending to the trainees.';
                        classes = 'alert-success';
                    }else{  //fail
                        msg_en = '<i class="fa fa-times" aria-hidden="true"></i> Error when sending evaluations!. Do the following please: 1) Select Course & session. 2) Click Search. 3) Check the trainees list. 4) Click Send Evaluation.';
                        classes = 'alert-warning';
                    }

                    var msg_element = document.getElementById('msg');
                    msg_element.classList.remove("alert-success", "alert-info", "alert-danger", "alert-warning");
                    msg_element.classList.add(classes);

                    $("#msg").html(msg_en);
                    $('#msg').css("display", "inline-block");
                    // // console.log(data);
                    // // ===========================

                },
                error:function(e){
                    $('[name="send_evaluation"]').removeAttr('disabled');
                    $('[name="send_evaluation"]').html(btnSubmitHtml);

                    msg = 'Error! There is an issue when sending evaluations to trainees; which is: <br>' + e;
                    $("#msg").html(msg + '<br>' + e.status + ' : ' + e.statusText,e.responseText);
                    $('#msg').css("display", "inline-block");
                    // console.log(e);
                },
            });
            return false;
        });


        // ===== Select One check =======

        $("span.to_enable").click(function(e){
            var itemId=$(this).attr("id");
            $('[value="'+itemId+'"]').removeAttr("disabled");
            $('[value="'+itemId+'"]').removeClass("hide");
            $(this).addClass('hide');
        });
        // ================================
    });
</script>
