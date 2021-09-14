@extends(FRONT.'.education.layouts.master')

@include('SEO.infa', ['infa_id'=>78])

@section('content')
    <?php use App\Helpers\Recaptcha; ?>
    {!! Recaptcha::script() !!}

    @include(FRONT.'.education.Html.page-header', ['title'=>__('education.courses'). ' | '.$course->trans_title])
    <div class="main-content py-5">
        <div class="container container-padding">

            @include(FRONT.'.education.products.register.important-notes')

            @include(FRONT.'.education.products.register.title')

            @include('front.education.Html.alert')
            {{-- @include('front.education.Html.errors') --}}

            <div class="row">
                <div class="col mt-3">
                    <div class="form-wrapper">
                        <div class="row">
                            <form class="row" action="{{route('education.courses.interest.submit', ['slug'=>$course->slug])}}" method="post">
                                <div class="col-md-12">
                                    @csrf
                                    <input type="hidden" name="course_id" value="{{old('course_id', $course->id)}}">
                                    {!! Recaptcha::execute() !!}
                                    <div class="row">

                                    {!! Builder::Input('course', 'course', $course->trans_title, ['col'=>'col-md-6', 'attr'=>'disabled="disabled"']) !!}

                                    <div class="col-lg-6 col-md-6">
                                        <div class="form-group">
                                            <label>{{__('education.Email')}}</label><br>
                                            <input type="text" name="email" value="{{old('email')}}" class="form-control @error('email') is-invalid @enderror" placeholder="{{__('education.Email')}}">
                                            @error('email')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6">
                                        <div class="form-group">
                                            <label>{{__('education.Full Name')}}</label><br>
                                            <input type="text" name="en_name" value="{{old('en_name')}}" class="form-control @error('en_name') is-invalid @enderror">
                                            @error('en_name')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6">
                                        <div class="form-group">
                                            <label>{{__('education.Mobile')}}</label><br>
                                            <input type="text" name="mobile" value="{{old('mobile')}}" class="form-control @error('mobile') is-invalid @enderror">
                                            @error('mobile')
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

                                    <div class="row submit_loading_div" style="padding-top: 15px;border-top: 1px solid #e5e5e5;margin-top: 10px;">
                                        <div class="col-lg-6 col-md-6">
                                            <button type="submit" class="btn btn-primary btn-block btn-lg">{!! __('education.send') !!}</button>
                                        </div>
                                    </div>
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
                    url:"{{route('education.courses.register.autofill')}}",
                    data:{
                        email:email,
                    },
                    success:function(data){
                        console.log(data);
                        jQuery('[name="en_name"]').val(data.name);
                        jQuery('[name="gender_id"]').val(data.gender_id);
                        jQuery('[name="job_title"]').val(data.job_title);
                        jQuery('[name="mobile"]').val(data.mobile);
                        jQuery('[name="company"]').val(data.company);
                        jQuery('[name="country_id"]').val(data.country_id);
                    }
                });
            }
        });
    </script>
@endsection
