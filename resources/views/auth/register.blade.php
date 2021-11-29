@extends('layouts.auth')

@section('content')
    <div class="signin">
        <div class="logo"><img src="{{CustomAsset('assets/images/logo.png')}}" alt=""></div>
        <div class="signin-form">
            <div class="row">
                <div class="col-md-6">
                    <form action="{{route('register')}}" method="POST">
                        @csrf
                        <h3>{{ __('education.Sign In') }}</h3>
                        <p class="bold">{{ __('education.You Have Account?') }} <a href="{{CustomRoute('login')}}">{{ __('education.Login') }}</a></p>
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

                            <input type="text" name="en_name" value="{{ old('en_name') }}"   class="form-control" placeholder="{{ __('education.Name') }}">
                        </div>
                        @error('en_name')
                        <span style="color:#f93d3d" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

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
                            <input type="password" name="password_confirmation"  class="form-control" placeholder="{{ __('Confirm Password') }}">
                        </div>
                        @error('password_confirmation')
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

                        <button class="btn btn-primary bold w-100 py-2">{{ __('Register') }}</button>

{{--                        <p class="divider bold text-center"><span>Or Sign In With</span></p>--}}

                        {{--                    <div class="social-media text-center">--}}
                        {{--                        <a href="#">--}}
                        {{--                            <svg xmlns="http://www.w3.org/2000/svg" width="13.367" height="10.863" viewBox="0 0 13.367 10.863">--}}
                        {{--                                <path id="Path_235" data-name="Path 235" d="M269.007,148.927a7.8,7.8,0,0,0,12-6.926,5.558,5.558,0,0,0,1.368-1.42,5.469,5.469,0,0,1-1.575.432A2.746,2.746,0,0,0,282,139.5a5.5,5.5,0,0,1-1.741.666,2.744,2.744,0,0,0-4.673,2.5,7.783,7.783,0,0,1-5.652-2.865,2.745,2.745,0,0,0,.848,3.662,2.733,2.733,0,0,1-1.242-.343,2.746,2.746,0,0,0,2.2,2.724,2.745,2.745,0,0,1-1.238.046,2.743,2.743,0,0,0,2.561,1.9A5.515,5.515,0,0,1,269.007,148.927Z" transform="translate(-269.007 -139.296)" fill="#222221"/>--}}
                        {{--                            </svg>--}}
                        {{--                        </a>--}}
                        {{--                        <a href="#">--}}
                        {{--                            <svg xmlns="http://www.w3.org/2000/svg" width="17.704" height="10.9" viewBox="0 0 17.704 10.9">--}}
                        {{--                                <g id="Group_239" data-name="Group 239" transform="translate(0)">--}}
                        {{--                                    <path id="Path_236" data-name="Path 236" d="M331.872,144.391H326.8l.006,1.451h3.417a3.354,3.354,0,0,1-3.325,2.851,3.976,3.976,0,1,1,2.7-6.891l1.122-.967a5.45,5.45,0,1,0-3.824,9.332,4.937,4.937,0,0,0,4.979-5.215Z" transform="translate(-321.452 -139.268)" fill="#222221"/>--}}
                        {{--                                    <path id="Path_237" data-name="Path 237" d="M345.457,146.049h-2.092v-2.092h-1.335v2.092h-2.092v1.335h2.092v2.092h1.335v-2.092h2.092Z" transform="translate(-327.753 -140.866)" fill="#222221"/>--}}
                        {{--                                </g>--}}
                        {{--                            </svg>--}}
                        {{--                        </a>--}}
                        {{--                        <a href="#">--}}
                        {{--                            <svg xmlns="http://www.w3.org/2000/svg" width="6.999" height="15.164" viewBox="0 0 6.999 15.164">--}}
                        {{--                                <path id="Path_238" data-name="Path 238" d="M386.265,151.2h3.053v-7.647h2.13l.227-2.56h-2.358v-1.458c0-.6.121-.842.705-.842h1.652v-2.657h-2.115c-2.272,0-3.3,1-3.3,2.916v2.041h-1.589v2.592h1.589Z" transform="translate(-384.676 -136.033)" fill="#222221"/>--}}
                        {{--                            </svg>--}}
                        {{--                        </a>--}}
                        {{--                        <a href="#">--}}
                        {{--                            <svg xmlns="http://www.w3.org/2000/svg" width="13.756" height="13.756" viewBox="0 0 13.756 13.756">--}}
                        {{--                                <g id="Group_242" data-name="Group 242" transform="translate(0 0)">--}}
                        {{--                                    <path id="Path_240" data-name="Path 240" d="M442.164,138.34c1.836,0,2.054.007,2.78.04a3.811,3.811,0,0,1,1.277.237,2.273,2.273,0,0,1,1.305,1.305,3.81,3.81,0,0,1,.237,1.277c.033.726.04.943.04,2.78s-.007,2.054-.04,2.779a3.811,3.811,0,0,1-.237,1.277,2.273,2.273,0,0,1-1.305,1.305,3.8,3.8,0,0,1-1.277.237c-.726.033-.943.04-2.78.04s-2.054-.007-2.779-.04a3.8,3.8,0,0,1-1.277-.237,2.278,2.278,0,0,1-1.305-1.305,3.8,3.8,0,0,1-.237-1.277c-.033-.725-.04-.943-.04-2.779s.007-2.054.04-2.78a3.8,3.8,0,0,1,.237-1.277,2.278,2.278,0,0,1,1.305-1.305,3.816,3.816,0,0,1,1.277-.237c.725-.033.943-.04,2.779-.04m0-1.239c-1.868,0-2.1.009-2.836.042a5.053,5.053,0,0,0-1.67.32,3.517,3.517,0,0,0-2.011,2.012,5.034,5.034,0,0,0-.32,1.67c-.034.734-.042.968-.042,2.836s.008,2.1.042,2.836a5.034,5.034,0,0,0,.32,1.67,3.517,3.517,0,0,0,2.011,2.012,5.071,5.071,0,0,0,1.67.32c.734.033.968.041,2.836.041s2.1-.008,2.836-.041a5.071,5.071,0,0,0,1.67-.32,3.521,3.521,0,0,0,2.012-2.012,5.053,5.053,0,0,0,.32-1.67c.034-.734.042-.968.042-2.836s-.008-2.1-.042-2.836a5.053,5.053,0,0,0-.32-1.67,3.521,3.521,0,0,0-2.012-2.012,5.053,5.053,0,0,0-1.67-.32c-.734-.033-.968-.042-2.836-.042" transform="translate(-435.286 -137.101)" fill="#222221"/>--}}
                        {{--                                    <path id="Path_241" data-name="Path 241" d="M443.894,142.178a3.532,3.532,0,1,0,3.532,3.532,3.532,3.532,0,0,0-3.532-3.532m0,5.824a2.293,2.293,0,1,1,2.293-2.293A2.292,2.292,0,0,1,443.894,148" transform="translate(-437.016 -138.832)" fill="#222221"/>--}}
                        {{--                                    <path id="Path_242" data-name="Path 242" d="M451.689,141.539a.825.825,0,1,1-.825-.825.825.825,0,0,1,.825.825" transform="translate(-440.314 -138.333)" fill="#222221"/>--}}
                        {{--                                </g>--}}
                        {{--                            </svg>--}}
                        {{--                        </a>--}}
                        {{--                    </div>--}}
                    </form>
                </div>
                <div class="col-md-6 text-center">
                    <img src="{{CustomAsset('assets/images/signin.png')}}" alt="">
                </div>
            </div>
        </div>
        <div class="bottom-bg">

        </div>
    </div>
@endsection
