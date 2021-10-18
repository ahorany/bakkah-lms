@extends(FRONT.'.education.layouts.master')

@section('useHead')
    <title>{{__('education.Login')}} | {{ __('home.DC_title') }}</title>
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
}

</style>
<div style="background-image:url('{{CustomAsset('images/background.png')}}'); background-repeat: no-repeat; background-size: cover; padding: 150px 0;">
    <div class="row m-0 justify-content-center">
        <div class="col-md-7 form-login">
            <div class="pb-4" style="background-image:url('{{CustomAsset('images/card.png')}}');background-repeat: no-repeat; background-size: cover; background-position:right;">
            <form action="{{route('user.loginSubmit')}}" method="POST" style="width:50%; padding:20px 65px;">
                @csrf
                <div class="my-4">
                    <h2 class="mb-4">{{ __('education.Sign In') }}</h2>
                    <p>{{ __('education.New user?') }} <a href="#">Create an account</a> </p>
                    {{-- <p>{{__('education.Welcome! Please, fill email and password to sign in into your account.')}}</p> --}}
                </div>
                @if(request()->has('redirectTo'))
                    <input type="hidden" name="redirectTo" value="{{ request()->redirectTo }}">
                @endif

                @if(request()->has('redirect'))
                    <input type="hidden" name="redirect" value="{{ request()->redirect }}">
                    <input type="hidden" name="action" value="wishlist">
                    <input type="hidden" name="option" value="{{ request()->option }}">
                    <input type="hidden" name="session_id" value="{{ request()->session_id }}">
                @endif

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
                    <input type="password" name="password" placeholder="{{ __('education.Password') }}" class="form-control @error('password') is-invalid @enderror">
                    <i class="fas fa-shield-alt"></i>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group d-flex justify-content-between align-items-center">
                    <div>
                        <input id="save" type="checkbox" name="checkbox">
                        <label style="color: #222222; margin: 0;" for="save">Keep me signed in</label>
                        {{-- <label style="color: #767676; margin: 0;" for="save">Save Password</label> --}}
                    </div>
                    {{-- <a style="color: #242a2e" href="{{ url('password/reset') }}" class="my-3">{{ __('education.Forgot Your Password?') }}</a> --}}
                </div>

                {{-- <a href="{{ url('password/reset') }}" class="d-block my-3">{{ __('education.Forgot Your Password?') }}</a> --}}

                <button class="btn btn-primary btn-block mb-3">{{__('education.Sign In')}}</button>

                <hr class="mt-5">
                <p class="or">OR Sign In With</p>

                <div class="social-media text-center my-4">

                    <a href="#" style="color: #000; border: 1px solid #000;"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" style="color: #000; border: 1px solid #000;"><i class="fab fa-twitter"></i></a>
                    <a href="#" style="color: #000; border: 1px solid #000;"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" style="color: #000; border: 1px solid #000;"><i class="fab fa-google-plus-g"></i></a>
                    {{-- <a href="#" style="background: #3b5999"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" style="background: #55acee"><i class="fab fa-twitter"></i></a>
                    <a href="#" style="background: #3c83d9"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" style="background: #fb4d3e"><i class="fab fa-google-plus-g"></i></a> --}}
                </div>

            </form>
            {{-- <div ">
                <img src="" />
            </div> --}}
        </div>
        </div>
    </div>
</div>

@endsection
