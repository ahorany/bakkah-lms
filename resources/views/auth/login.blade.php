@extends('layouts.auth')

@section('content')
    <div class="signin">
        <div class="logo"><img src="{{CustomAsset('assets/images/logo.png')}}" alt=""></div>
        <div class="signin-form container">
            <div class="row">
                <div class="col-md-6">
                    <form action="{{route('login')}}" method="POST">
                        @csrf
                        <h3 class="mb-4">{{ __('education.Sign In') }}</h3>
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

                            <input type="text"  name="email" value="{{ old('email') }}"   class="form-control" placeholder="{{ __('education.Email or Username') }}">
                        </div>
                        @error('email')
                        <span style="color:#f93d3d" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                        @enderror
                        <div class="mb-3 mt-1 form-group">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22">
                                <g id="Icon_" data-name="Icon " transform="translate(-0.119 0.275)">
                                    <rect id="Area_ICON:feather_x_SIZE:MEDIUM_STATE:DEFAULT_STYLE:STYLE2_" data-name="Area [ICON:feather/x][SIZE:MEDIUM][STATE:DEFAULT][STYLE:STYLE2]" width="22" height="22" transform="translate(0.119 -0.275)" fill="#222" opacity="0"/>
                                    <g id="Icon" transform="translate(2.362 1.718)">
                                        <rect id="Rect" width="16.37" height="10.004" rx="2" transform="translate(0 8.185)" fill="none" stroke="#3d3d3d" stroke-linecap="round" stroke-linejoin="round" stroke-width="1"/>
                                        <path id="Path" d="M5.833,9.852V6.214a4.548,4.548,0,0,1,9.1,0V9.852" transform="translate(-2.195 -1.667)" fill="none" stroke="#3d3d3d" stroke-linecap="round" stroke-linejoin="round" stroke-width="1"/>
                                    </g>
                                </g>
                            </svg>
                            <input type="password" name="password"  class="form-control" placeholder="{{ __('education.Password') }}">
                        </div>
                        @error('password')
                        <span style="color:#f93d3d" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                        @enderror
                        <div class="mb-3 form-group">
                            <label class="checkbox-lable"> Keep me signed in
                                <input id="save" type="checkbox" name="checkbox">
                                <span class="checkbox-mark"></span>
                            </label>
                        </div>
                        <button class="btn btn-primary bold w-100 py-2">{{__('education.Sign In')}}</button>
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
