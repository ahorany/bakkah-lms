@extends(FRONT.'.education.layouts.master')

@section('useHead')
    <title>{{__('education.Register')}} | {{ __('home.DC_title') }}</title>
@endsection
@section('content')

<style>
p.or{
    width: 50%;
}
input.form-control {
    background: #F8F8F8;
    border: none;
    box-shadow: none;
    padding-left: 45px;
}
input.form-control::placeholder {
    color: #222222;
}
.form-login .form-group i {
    position: absolute;
    top: 15px;
    left: 20px;
    color: #222222;
    width: max-content;
}

</style>
<div class="login" style="background-image:url('{{CustomAsset('images/background.png')}}'); background-repeat: no-repeat; background-size: cover;">
    <div class="row m-0 justify-content-center">
        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12 form-login">
            <div class="pb-4" style="background-image:url('{{CustomAsset('images/card.png')}}');background-repeat: no-repeat; background-size: cover; background-position:right; transform: translate(0, 25%);">
            <form action="{{route('user.registerSubmit')}}" method="POST" style="width:50%; padding:20px 65px;">
                @csrf
                <div class="my-4">
                    <h2>{{__('education.Sign Up')}}</h2>
                   <p>{{ __('education.do you have an account?') }} <a href="{{CustomRoute('user.login')}}">Login</a> </p>
                    {{-- <p>{{__('education.Welcome! Please, fill email and password to sign in into your account.')}}</p> --}}
                </div>

                @if(request()->has('redirect'))
                    <input type="hidden" name="redirect" value="{{ request()->redirect }}">
                    <input type="hidden" name="action" value="wishlist">
                    <input type="hidden" name="option" value="{{ request()->option }}">
                    <input type="hidden" name="session_id" value="{{ request()->session_id }}">
                @endif


                <div class="form-group position-relative">
                    <input type="text" name="en_name" value="{{ old('en_name') }}" placeholder="{{ __('education.Full Name') }}" class="form-control @error('en_name') is-invalid @enderror">
                    <i class="fas fa-envelope"></i>
                    @error('en_name')
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>


                <div class="form-group position-relative">
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="{{ __('education.Email or Username') }}" class="form-control @error('email') is-invalid @enderror">
                    <i class="fas fa-envelope"></i>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>



                <div class="form-group position-relative">
                    <input type="text" name="mobile" value="{{ old('mobile') }}" placeholder="{{ __('education.Mobile') }}" class="form-control @error('mobile') is-invalid @enderror">
                    <i class="fas fa-envelope"></i>
                    @error('mobile')
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>


                <div class="form-group position-relative">
                    <input type="password" name="password" placeholder="{{ __('education.Password') }}" class="form-control @error('password') is-invalid @enderror">
                    <i class="fas fa-shield-alt"></i>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group position-relative">
                    <input type="password" name="password_confirmation" placeholder="{{ __('admin.password_confirmation') }}" class="form-control">
                </div>




                <div class="form-group d-flex justify-content-between align-items-center">
                    <div>
                        <input id="save" type="checkbox" name="checkbox">
                        <label style="color: #222222; margin: 0;" for="save">Agree with <span style="color:#000;">Term & Conditons</span></label>
                    </div>
                </div>

                <button class="btn btn-primary btn-block mb-3">{{__('education.Register')}}</button>

{{--                <hr class="mt-5">--}}
{{--                <p class="or">OR Sign In With</p>--}}

{{--                <div class="social-media text-center my-4">--}}

{{--                    <a href="#" style="color: #000; border: 1px solid #000;"><i class="fab fa-facebook-f"></i></a>--}}
{{--                    <a href="#" style="color: #000; border: 1px solid #000;"><i class="fab fa-twitter"></i></a>--}}
{{--                    <a href="#" style="color: #000; border: 1px solid #000;"><i class="fab fa-linkedin-in"></i></a>--}}
{{--                    <a href="#" style="color: #000; border: 1px solid #000;"><i class="fab fa-google-plus-g"></i></a>--}}
{{--                    --}}{{-- <a href="#" style="background: #3b5999"><i class="fab fa-facebook-f"></i></a>--}}
{{--                    <a href="#" style="background: #55acee"><i class="fab fa-twitter"></i></a>--}}
{{--                    <a href="#" style="background: #3c83d9"><i class="fab fa-linkedin-in"></i></a>--}}
{{--                    <a href="#" style="background: #fb4d3e"><i class="fab fa-google-plus-g"></i></a> --}}
{{--                </div>--}}

            </form>
        </div>
        </div>
    </div>
</div>

@endsection
