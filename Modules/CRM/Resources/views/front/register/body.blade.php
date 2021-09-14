<?php use App\Helpers\Recaptcha;
?>
{!! Recaptcha::script() !!}

@include(FRONT.'.education.Html.page-header', ['title'=>__('education.RFP')])
<div class="main-content py-5" id="app-register-form">
    <div class="container container-padding">

        {{-- @include(FRONT.'.education.courses.register.important-notes') --}}
        {{-- @include(FRONT.'.education.courses.register.title') --}}
        @include('front.education.Html.alert')
        {{--@include('front.education.Html.errors')--}}
        {{-- {{$errors??null}} --}}
        <div class="row">
            <div class="col mt-3">
                <div class="form-wrapper">
                    <div class="row">
                        <form id="page_content" class="row" action="{{$route}}" method="post" enctype="multipart/form-data">
                            <div class="order-2 order-md-1 col-md-12">
                                @csrf
                                {!! Recaptcha::execute() !!}
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        <div class="form-group">
                                            <label>{{__('education.Email')}} <span class="text-danger">*</span></label><br>
                                            <?php
                                                $val_email = $cartMaster->rfpGroup->organization->email??null;
                                                $val_email = old('email')??$val_email;
                                            ?>
                                            <input type="text" name="email" value="{{$val_email}}" class="form-control @error('email') is-invalid @enderror" placeholder="{{__('education.Email')}}">
                                            @error('email')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6">
                                        <div class="form-group">
                                            <label>{{__('education.Org Name')}} <span class="text-danger">*</span></label><br>
                                            <?php
                                                $val_en_title = $cartMaster->rfpGroup->organization->en_title??null;
                                                $val_en_title = old('en_title')??$val_en_title;
                                            ?>
                                            <input type="text" name="en_title" value="{{$val_en_title}}" class="form-control @error('en_title') is-invalid @enderror" placeholder="{{__('education.Org Name')}}">
                                            @error('en_title')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6">
                                        <div class="form-group">
                                            <label>{{__('education.Full Name')}} <span class="text-danger">*</span></label><br>
                                            <?php
                                                $val_en_name = $cartMaster->rfpGroup->organization->en_name??null;
                                                $val_en_name = old('en_name')??$val_en_name;
                                            ?>
                                            <input type="text" name="en_name" value="{{$val_en_name}}" class="form-control @error('en_name') is-invalid @enderror" placeholder="{{__('education.Full Name')}}">
                                            @error('en_name')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6">
                                        <div class="form-group">
                                            <label>{{__('education.Job Title')}}</label><br>
                                            <?php
                                                $val_job_title = $cartMaster->rfpGroup->organization->job_title??null;
                                                $val_job_title = old('job_title')??$val_job_title;
                                            ?>
                                            <input type="text" name="job_title" value="{{$val_job_title}}" class="form-control @error('job_title') is-invalid @enderror" placeholder="{{__('education.Job Title')}}">
                                            @error('job_title')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6">
                                        <div class="form-group">
                                            <label>{{__('education.Mobile')}} <span class="text-danger">*</span></label><br>
                                            <?php
                                                $val_mobile = $cartMaster->rfpGroup->organization->mobile??null;
                                                $val_mobile = old('mobile')??$val_mobile;
                                            ?>
                                            <input type="text" name="mobile" value="{{$val_mobile}}" class="form-control @error('mobile') is-invalid @enderror" placeholder="{{__('education.Mobile')}}">
                                            @error('mobile')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6">
                                        <div class="form-group">
                                            <label>{{__('education.address')}}</label><br>
                                            <?php
                                                $val_address = $cartMaster->rfpGroup->organization->address??null;
                                                $val_address = old('address')??$val_address;
                                            ?>
                                            <input type="text" name="address" value="{{$val_address}}" class="form-control @error('address') is-invalid @enderror" placeholder="{{__('education.address')}}">
                                            @error('address')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                    </div>

                                </div>

                                <div class="row" style="padding-top: 15px;border-top: 1px solid #e5e5e5;margin-top: 10px;">

                                    {{-- <style>
                                        select[readonly]
                                            {
                                                pointer-events: none;
                                            }
                                    </style> --}}
                                    <?php
                                        $val_id = $cartMaster->id??null;
                                        $dis = 'pointer-events: none; -webkit-user-modify: read-only;';
                                    ?>

                                            <div class="col-lg-6 col-md-6">
                                                <div class="form-group">
                                                    <label>{{__('admin.course_name')}}</label>
                                                        <?php
                                                            $val_course_id = $cartMaster->rfpGroup->course_id??null;
                                                            request()->val_course_id = $val_course_id;
                                                        ?>
                                                    <select name="course_id" @change="courseChange($event.target.value)" class="form-control @error('course_id') is-invalid @enderror " @if(!is_null($val_id)) style="{{$dis}}" @endif>
                                                        <option value="-1">{{__('admin.choose_value')}}</option>
                                                        @foreach($all_courses as $all_course)
                                                            <option value="{{$all_course->id}}" {{($val_course_id==$all_course->id)?'selected="selected"':''}}>{{$all_course->trans_title}}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('course_id')
                                                        <small class="text-danger">{{$message}}</small>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-6">
                                                <div class="form-group">
                                                    <label>{{__('admin.session_id')}}</label>
                                                    <?php
                                                        $val_session_id = $cartMaster->rfpGroup->session_id??null;
                                                        request()->val_session_id = $val_session_id;
                                                    ?>
                                                    <select name="session_id" class="form-control @error('session_id') is-invalid @enderror " @if(!is_null($val_id)) style="{{$dis}}" @endif>
                                                        <option value="-1">@{{ session_choose_value }}</option>
                                                        <option v-for="(list , index) in sessions" :value="list.id" :selected="list.id==val_session_id">@{{list.json_title}}</option>
                                                    </select>
                                                    @error('session_id')
                                                        <small class="text-danger">{{$message}}</small>
                                                    @enderror
                                                </div>
                                            </div>

                                @if(is_null($val_id))

                                    <div class="col-lg-6 col-md-6 align-self-center">
                                        <div class="form-group">
                                            <label>{{__('education.Template to add candidates information')}}</label><br>
                                            <a href="{{CustomAsset('upload/excel/candidates.xlsx')}}" download=""><span>{{__('education.Download Excel')}}</span> <i class="fas fa-download card p-1 shadow main-color"></i></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="form-group">

                                            <label>{{__('education.Upload the filled excel sheet')}}</label><br>
                                            <input type="file" name="excel_file"class="form-control @error('excel_file') is-invalid @enderror" placeholder="{{__('education.upload_users')}}">
                                            @error('excel_file')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror

                                        </div>
                                    </div>

                                @endif
                                </div>

                                <div class="row submit_loading_div" style="padding-top: 15px;border-top: 1px solid #e5e5e5;margin-top: 10px;">
                                    <div class="col-lg-6 col-md-6">
                                        <button type="submit" class="btn btn-primary btn-block btn-lg">{!! __('education.submit') !!}</button>
                                    </div>
                                </div>

                            </div>

                        </form>
                        <!--end-->

                        <div class="col-lg-12 col-md-12 p-0">
                            <div class="form-group">
                                <?php $val_id = $cartMaster->id??null; ?>
                                    @if(!is_null($val_id))
                                        {{-- Start Candidates --}}
                                        <style>
                                            tr.active {
                                                background-color: #cfffc2 !important;
                                            }
                                        </style>
                                            <div class="card mt-4">
                                                <div class="card-header">
                                                    <h6 class="mb-0" style="color: #fb4400;"><i class="fa fa-users" aria-hidden="true"></i> {{__('education.Candidates')}}</h6>
                                                </div>

                                                <div class="card-body p-0">
                                                    <table class="table table-striped table-hover table-total-info">
                                                        <tr>
                                                            <th style="width: 5%">#</th>
                                                            <th>{{__('education.Candidate Name')}}</th>
                                                            <th>{{__('education.Email')}}</th>
                                                            <th>{{__('education.Mobile')}}</th>
                                                            <th>{{__('education.Job Title')}}</th>
                                                            <th>{{__('education.Country')}}</th>
                                                            <th>{{__('education.registered_at')}}</th>
                                                            <th>{{__('education.delete')}}</th>
                                                        </tr>
                                                        @foreach ($cartMaster->carts as $cart)
                                                        <tr data-tr="{{$cart->id}}" {{(($cart->userId->id/2)==0) ? 'class=active' : '' }}>
                                                            <td>{{$loop->iteration}}</td>
                                                            <td>{{$cart->userId->trans_name??null}}</td>
                                                            <td>{{$cart->userId->email??null}}</td>
                                                            <td>{{$cart->userId->mobile??null}}</td>
                                                            <td>{{$cart->userId->job_title??null}}</td>
                                                            <td>{{$cart->userId->country??null}}</td>
                                                            <td>{{date('d-M-Y', strtotime($cart->registered_at))}}</td>
                                                            <td><button data-id="{{$cart->id}}" class="btn btn-sm btn-danger btn-table btn-delete" style="visibility:visible;">
                                                                <i class="fa fa-trash"></i> {{__('admin.delete')}}
                                                            </button></td>
                                                        </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </div>
                                        {{-- End Candidates --}}
                                    @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
jQuery(function(){
    jQuery('[name="email"]').focusout(function() {
        var email = jQuery(this).val();
        autofill(email);
    });

    jQuery('[name="email"]').keydown(function( event ) {
        if ( event.which == 13 ) {
            event.preventDefault();
            var email = jQuery(this).val();
            autofill(email);
        }
    });

    function autofill(email){
        jQuery.ajax({
            type:'get',
            url:"{{route('crm::rfq.register.autofill')}}",
            data:{
                email:email,
            },
            success:function(data){
                if(data.title) {
                    jQuery('[name="en_title"]').val(data.title);
                    jQuery('[name="mobile"]').val(data.mobile);
                    jQuery('[name="en_name"]').val(data.name);
                    jQuery('[name="job_title"]').val(data.job_title);
                    jQuery('[name="address"]').val(data.address);
                }
            }
        });
    }

    jQuery('.btn-delete').click(function(){

        var frm = confirm('Sure To Delete');
        if(frm){
        var id = jQuery(this).data('id');
        jQuery.ajax({
                type:'get',
                url:"{{route('crm::rfq.register.delete')}}",
                data:{
                    cart_id:id,
                },
                success:function(data){
                    jQuery('tr[data-tr="'+id+'"]').remove();
                },
                errors:function(e){
                    console.log(e);
                },
            });
        }
        return false;
    });

});
</script>
