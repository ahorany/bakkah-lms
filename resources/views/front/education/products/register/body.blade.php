<?php use App\Helpers\Recaptcha; ?>
{!! Recaptcha::script() !!}

@include(FRONT.'.education.Html.page-header', ['title'=>__('education.courses'). ' | '.$course->trans_title])
<div class="main-content py-5" id="app-register-form">
    <div class="container container-padding">

        {{-- @include(FRONT.'.education.courses.register.important-notes') --}}
        {{-- @include(FRONT.'.education.courses.register.title') --}}
        @include('front.education.Html.alert')
        @include('front.education.Html.errors')
        {{$SessionHelper->SetCourse($session)}}

        <div class="row">
            <div class="col mt-3">
                <div class="form-wrapper">
                    <div class="row">

                        <form class="row" action="{{route('education.courses.register.submit', ['slug'=>$course->slug, 'session_id'=>request()->session_id??null])}}" method="post" enctype="multipart/form-data">
                            <div class="order-2 order-md-1 col-md-8">
                                @csrf
                                {!! Recaptcha::execute() !!}
                                <div class="row">
                                    <input type="hidden" name="session_id" value="{{request()->session_id??null}}">
                                    {{--<input type="hidden" name="discount_id" value="{{$session->discount_id??null}}">
                                    <input type="hidden" name="discount_percentage" value="{{NumberFormat($session->discount_value)}}">
                                    --}}

                                    {!! Builder::Input('course', 'course', $course->trans_title, ['col'=>'col-md-6', 'attr'=>'disabled="disabled"']) !!}

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{!! __('education.Special Offer Price') !!}</span></label><br>
                                            <?php
                                            $session_registration = App\Helpers\Lang::TransTitle($session->constant_name);
                                            if($session->constant_id==13){
                                                $session_registration .= ' | ';
                                                $session_registration .= App\Helpers\Date::IsoFormat($session->session_date_from);
                                            }
                                            ?>
                                            <input class="form-control" type="text" value="{{$session_registration??null}}" disabled="disabled">
                                        </div>
                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-lg-6 col-md-6">
                                        <div class="form-group">
                                            <label>{{__('education.Email')}} <span class="text-danger">*</span></label><br>
                                            <input type="text" name="email" value="{{old('email')}}" class="form-control @error('email') is-invalid @enderror" placeholder="{{__('education.Email')}}">
                                            @error('email')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6">
                                        <div class="form-group">
                                            <label>{{__('education.Gender')}}</label>
                                            <select name="gender_id" class="form-control @error('gender_id') is-invalid @enderror ">
                                                <option value="-1">{{__('education.choose')}}</option>
                                                @foreach($genders as $gender)
                                                    <option value="{{$gender->id}}" {{(old('gender_id')==$gender->id)?'selected="selected"':''}}>{{$gender->trans_name}}</option>
                                                @endforeach
                                            </select>
                                            @error('gender_id')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6">
                                        <div class="form-group">
                                            <label>{{__('education.Full Name')}} <span class="text-danger">*</span></label><br>
                                            <input type="text" name="en_name" value="{{old('en_name')}}" class="form-control @error('en_name') is-invalid @enderror" placeholder="{{__('education.Full Name')}}">
                                            @error('en_name')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6">
                                        <div class="form-group">
                                            <label>{{__('education.Job Title')}}</label><br>
                                            <input type="text" name="job_title" value="{{old('job_title')}}" class="form-control @error('job_title') is-invalid @enderror" placeholder="{{__('education.Job Title')}}">
                                            @error('job_title')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6">
                                        <div class="form-group">
                                            <label>{{__('education.Company')}}</label><br>
                                            <input type="text" name="company" value="{{old('company')}}" class="form-control @error('company') is-invalid @enderror" placeholder="{{__('education.Company')}}">
                                            @error('company')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6">
                                        <div class="form-group">
                                            <label>{{__('education.Mobile')}} <span class="text-danger">*</span></label><br>
                                            <input type="text" name="mobile" value="{{old('mobile')}}" class="form-control @error('mobile') is-invalid @enderror" placeholder="{{__('education.Mobile')}}">
                                            @error('mobile')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6">
                                        <div class="form-group">
                                            <label>{{__('education.Country')}}</label><br>
                                            <select name="country_id" class="form-control @error('country_id') is-invalid @enderror">
                                                <option value="-1">{{__('education.choose')}}</option>
                                                @foreach($countries as $country)
                                                    <option value="{{$country->id}}" {{(old('country_id')==$country->id)?'selected="selected"':''}}>{{$country->trans_name}}</option>
                                                @endforeach
                                            </select>
                                            @error('country_id')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                    </div>

                                </div>

                                @if(isset($questions))
                                    @includeWhen(!is_null($questions), 'front.education.products.register.questions')
                                @endif

                                <div class="row">

                                    <br>
                                    <div class="col-lg-12 col-md-12">
                                        <p>{{__('education.announcements and news')}}</p>
                                        <label class="chk_container">{{__('education.I want to receive news about upcoming courses')}}
                                            <input type="checkbox" name="mail_subscribe" value="1">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>

                                    <div class="col-lg-12 col-md-12">
                                        <?php
                                            // $terms = "terms-and-conditions";
                                            // if($course->partner_id == 12){
                                            //     $terms = "cipd-terms-and-conditions";
                                            // }
                                        ?>
                                        <label class="chk_container">{{__('education.By registering, you hereby declare that you agree to our')}} <a target="_blank" href="{{route('education.static.static-page', ["post_type"=>"terms-and-conditions"])}}">{{__('education.Terms and Conditions')}}</a> ,
                                            <a target="_blank" href="{{route('education.static.static-page',["post_type"=>"privacy-policy"])}}">{{__('education.Privacy Policy')}}</a> {{__('education.and')}} <a target="_blank" href="{{route('education.static.static-page', ["post_type"=>"cookies-policy"])}}">{{__('education.Cookies Policy')}}</a>.
                                            <input type="checkbox" name="pp_agree" value="1">
                                            <span class="checkmark"></span>
                                        </label>
                                        @error('pp_agree')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row submit_loading_div" style="padding-top: 15px;border-top: 1px solid #e5e5e5;margin-top: 10px;">
                                    <div class="col-lg-6 col-md-6">
                                        <button type="submit" class="btn btn-primary btn-block btn-lg">
                                            @if($SessionHelper->Price()==0)
                                                {!! __('education.Register') !!}
                                            @else
                                                {!! __('education.Register Pay Online') !!}
                                            @endif
                                        </button>
                                    </div>
                                </div>

                            </div>
                            <div class="order-1 order-md-2 col-md-4">
                                @include('front.education.products.components.summary.index', ['course'=>$course])
                                {{-- @include(FRONT.'.education.courses.register.order-summary.index') --}}
                            </div>
                        </form>
                        <!--end-->

                    </div>
                </div>
                <a href="{{route('education.courses.single', ['slug'=>$course->slug])}}" class="btn btn-secondary btn-block d-md-none mt-4">{{__('education.View Course')}} <i style="transform: scaleX(-1);" class="fas fa-reply"></i></a>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
    jQuery(function(){

        var trainingOptionFeaturess = {!! json_encode(old('trainingOptionFeatures', [])) !!};
        var trainingOptionFeaturesCount = Object.keys(trainingOptionFeaturess).length;
        if (trainingOptionFeaturesCount == 0) {
            $('[type="checkbox"]').prop("checked", false);
        }



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
                url:"{{route('education.courses.register.autofill')}}",
                data:{
                    email:email,
                },
                success:function(data){
                    if(data.name) {
                        jQuery('[name="en_name"]').val(data.name);
                        jQuery('[name="gender_id"]').val(data.gender_id);
                        jQuery('[name="job_title"]').val(data.job_title);
                        jQuery('[name="mobile"]').val(data.mobile);
                        jQuery('[name="company"]').val(data.company);
                        jQuery('[name="country_id"]').val(data.country_id);
                    }
                }
            });
        }

        jQuery('[name="q_12"]').siblings().hide();
        $('[name="q_11"]').change(function(){

            if(jQuery(this).is(':checked')){
                jQuery('[name="q_12"]').show();
                jQuery('[name="q_12"]').siblings().show();
            }
            else {
                jQuery('[name="q_12"]').hide();
                jQuery('[name="q_12"]').siblings().hide();
            }
        });

        $('#balanace_label').click(function() {
            $('#retrieved_code').toggle()
        })
    });
    </script>
@endpush
