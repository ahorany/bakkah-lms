@extends(FRONT.'.education.layouts.master')
@section('useHead')
    <title>{{ __('education.Register') }} | {{ __('home.DC_title') }}</title>
@endsection
@section('content')

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-4 form-login">
            <div class="card">
            <form action="{{route('user.registerSubmit')}}" method="POST">
                @csrf
                @if(request()->has('redirectTo'))
                    <input type="hidden" name="redirectTo" value="checkout">
                @endif
                <div class="text-center my-4">
                    <h2>{{__('education.Sign Up')}}</h2>
                    {{-- <p>Welcome!</p> --}}
                </div>
                @if(request()->has('redirect'))
                    <input type="hidden" name="redirect" value="{{ request()->redirect }}">
                    <input type="hidden" name="action" value="wishlist">
                    <input type="hidden" name="option" value="{{ request()->option }}">
                    <input type="hidden" name="session_id" value="{{ request()->session_id }}">
                @endif
                <div class="form-group">
                    <input type="text" name="en_name" value="{{ old('en_name') }}" placeholder="{{ __('education.Full Name') }}" class="form-control @error('en_name') is-invalid @enderror">
                    @error('en_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="{{ __('education.Email Address') }}" class="form-control @error('email') is-invalid @enderror">
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <input type="text" name="mobile" value="{{ old('mobile') }}" placeholder="{{ __('education.Mobile') }}" class="form-control @error('mobile') is-invalid @enderror">
                    @error('mobile')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>


                <div class="form-group">
                    <input type="password" name="password" placeholder="{{ __('education.Password') }}" class="form-control @error('password') is-invalid @enderror">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <input type="password" name="password_confirmation" placeholder="{{ __('admin.password_confirmation') }}" class="form-control">
                </div>

                <div class="form-group">
                    <input id="save" type="checkbox" name="checkbox">
                    <label style="color: #767676;" for="save">Agree with <span style="color:#000;">Term & Conditons</span></label>
                </div>

                <button class="btn btn-primary btn-block mb-3">{{__('education.Register')}}</button>

                <hr class="mt-5">
                <p class="or">OR</p>

                <div class="social-media text-center my-4">
                    <a href="#" style="background: #3b5999"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" style="background: #55acee"><i class="fab fa-twitter"></i></a>
                    <a href="#" style="background: #3c83d9"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" style="background: #fb4d3e"><i class="fab fa-google-plus-g"></i></a>
                </div>

                <?php
                $url = route('user.login');
                if(request()->has('redirectTo')) {
                    $url = route('user.login') . '/?redirectTo='. request()->redirectTo;
                }

                if(request()->has('redirect')) {
                    $url = route('user.login') . '/?redirect='. request()->redirect .'&action=wishlist&option=' . request()->option . '&session_id=' . request()->session_id;
                }
                ?>
                <p class="text-center">{{__('education.Already have an account?')}} <a href="{{$url}}">{{__('education.Login')}}</a></p>

            </form>
        </div>
        </div>
    </div>
</div>

@endsection
