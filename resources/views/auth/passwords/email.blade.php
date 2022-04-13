@extends('layouts.auth')

@section('content')
    <div class="signin">
        <div class="logo"><img src="{{CustomAsset('assets/images/logo.png')}}" alt=""></div>
        <div class="signin-form container">
            <div class="row">
                <div class="col-md-6">
                    @if (session('status'))
                        <div style="background: #51907d;padding: 15px;color: #fff;border-radius: 3px;" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="text-center mb-4">
                            <h2>Recover Password</h2>
                            <label style="color: #767676;" for="save">Enter your registered email below to receive password reset code</label>
                        </div>
                        <div class="mb-3 form-group">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22">
                                <g id="Icon_" data-name="Icon " transform="translate(0.176 0.486)">
                                    <rect id="Area_ICON:feather_x_SIZE:MEDIUM_STATE:DEFAULT_STYLE:STYLE2_" data-name="Area [ICON:feather/x][SIZE:MEDIUM][STATE:DEFAULT][STYLE:STYLE2]" width="22" height="22" transform="translate(-0.176 -0.486)" fill="#222" opacity="0"/>
                                    <g id="Icon" transform="translate(1.999 3.536)">
                                        <path id="Path" d="M10.318,17H24.864a1.824,1.824,0,0,1,1.818,1.818v10.91a1.824,1.824,0,0,1-1.818,1.818H10.318A1.824,1.824,0,0,1,8.5,29.728V18.818A1.824,1.824,0,0,1,10.318,17Z" transform="translate(-8.5 -17)" fill="none" stroke="#3d3d3d" stroke-linecap="round" stroke-linejoin="round" stroke-width="1"/>
                                        <path id="Path-2" data-name="Path" d="M26.683,25.5l-9.091,6.364L8.5,25.5" transform="translate(-8.5 -23.682)" fill="none" stroke="#3d3d3d" stroke-linecap="round" stroke-linejoin="round" stroke-width="1"/>
                                    </g>
                                </g>
                            </svg>

                            <input type="text"  name="email" value="{{ old('email') }}"   class="form-control" placeholder="Your Email">
                        </div>
                        @error('email')
                        <span style="color:#f93d3d" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                        @enderror

                       <button class="btn btn-primary bold w-100 mt-3 py-2" style="background: #fb4400;">Send Code</button>

                    </form>
                </div>

                <div class="col-md-6 text-center">
                    <img src="{{CustomAsset('assets/images/signin.png')}}" alt="">
                </div>
            </div>
        </div>
        <div class="bottom-bg"></div>
    </div>
@endsection
@if(env('reCAPTCHA_RUN'))
    <script src="https://www.google.com/recaptcha/api.js?render=<?= env('reCAPTCHA_site_key'); ?>"></script>
@endif
<script>
        @if(env('reCAPTCHA_RUN'))
    let form = document.querySelector("form")
    if(form){
        if((form.getAttribute('method')).toLowerCase() == "post" && (form.getAttribute('id')).toLowerCase() != "payment-form"){
            form.innerHTML += `<input type="hidden" id="recaptcha_response" name="recaptcha_response">`
            grecaptcha.ready(function() {
                grecaptcha.execute("<?= env('reCAPTCHA_site_key'); ?>", {action: "homepage"}).then(function(token) {
                    // Add your logic to submit to your backend server here.
                    document.getElementById('recaptcha_response').value=token;
                });
            });
        }
    }
    @endif
</script>

