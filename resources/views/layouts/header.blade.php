
<header class="navbar navbar-dark sticky-top bg-white flex-md-nowrap p-0 shadow lms-header">
    <a class="navbar-brand col-md-3 col-lg-3 col-xl-2 me-0 px-3" href="{{CustomRoute('user.home')}}"><img src="{{CustomAsset('assets/images/logo.png')}}" alt="{{__('education.header_title')}}"> <span class="d-none d-sm-block">BAKKAH
            <b>LMS</b></span></a>
    <button class="navbar-toggler position-absolute d-md-none collapsed  me-auto ms-3" type="button" data-bs-toggle="collapse"
            data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false"
            aria-label="Toggle navigation">
        <svg xmlns="http://www.w3.org/2000/svg" width="85.08" height="63.677" viewBox="0 0 85.08 63.677">
            <g id="Menu-01" transform="translate(-7.46 -20.133)">
                <path id="Path_462" data-name="Path 462" d="M7.47,24.45a14.117,14.117,0,0,1,1.04-2.14,4.912,4.912,0,0,1,4.17-2.17q37.32-.015,74.65,0a5.212,5.212,0,0,1,4.89,3.46c.1.28.21.56.32.84v1.99c-.11.28-.22.56-.32.84a5.245,5.245,0,0,1-4.89,3.47H12.68a5.236,5.236,0,0,1-4.89-3.46c-.1-.28-.21-.56-.32-.84C7.47,25.77,7.47,25.11,7.47,24.45Z"/>
                <path id="Path_463" data-name="Path 463" d="M92.53,52.97c-.11.28-.22.56-.32.84a5.245,5.245,0,0,1-4.89,3.47H12.67a5.236,5.236,0,0,1-4.89-3.46c-.1-.28-.21-.56-.32-.84V50.99c.1-.26.2-.51.29-.77a5.241,5.241,0,0,1,5-3.54H87.24a5.232,5.232,0,0,1,5,3.54c.09.26.19.51.29.76Z"/>
                <path id="Path_464" data-name="Path 464" d="M92.53,79.5c-.11.28-.22.56-.32.84a5.245,5.245,0,0,1-4.89,3.47H12.67a5.236,5.236,0,0,1-4.89-3.46c-.1-.28-.21-.56-.32-.84V77.52c.1-.26.2-.51.29-.77a5.241,5.241,0,0,1,5-3.54H87.24a5.232,5.232,0,0,1,5,3.54c.09.26.19.51.29.76Z"/>
            </g>
        </svg>

    </button>

    <ul class="navbar-nav">
        <li><a href="{{route('user.info')}}" class="nav-link"><svg xmlns="http://www.w3.org/2000/svg" width="28.474" height="28.474"
                                              viewBox="0 0 28.474 28.474">
                    <g id="Icon_feather-settings" data-name="Icon feather-settings"
                       transform="translate(-0.75 -0.75)">
                        <path id="Path_232" data-name="Path 232"
                              d="M20.857,17.178A3.678,3.678,0,1,1,17.178,13.5,3.678,3.678,0,0,1,20.857,17.178Z"
                              transform="translate(-2.191 -2.191)" fill="none" stroke="#000" stroke-linecap="round"
                              stroke-linejoin="round" stroke-width="1.5" />
                        <path id="Path_233" data-name="Path 233"
                              d="M24.06,18.665a2.023,2.023,0,0,0,.4,2.231l.074.074a2.454,2.454,0,1,1-3.47,3.47l-.074-.074a2.039,2.039,0,0,0-3.458,1.447v.208a2.452,2.452,0,0,1-4.9,0v-.11a2.023,2.023,0,0,0-1.324-1.851,2.023,2.023,0,0,0-2.231.4L9,24.538a2.454,2.454,0,1,1-3.47-3.47l.074-.074a2.039,2.039,0,0,0-1.447-3.458H3.952a2.452,2.452,0,0,1,0-4.9h.11a2.023,2.023,0,0,0,1.851-1.324,2.023,2.023,0,0,0-.4-2.231L5.436,9a2.454,2.454,0,1,1,3.47-3.47l.074.074a2.023,2.023,0,0,0,2.231.4h.1a2.023,2.023,0,0,0,1.226-1.851V3.952a2.452,2.452,0,0,1,4.9,0v.11A2.039,2.039,0,0,0,20.9,5.509l.074-.074a2.454,2.454,0,1,1,3.47,3.47l-.074.074a2.023,2.023,0,0,0-.4,2.231v.1a2.023,2.023,0,0,0,1.851,1.226h.208a2.452,2.452,0,0,1,0,4.9h-.11a2.023,2.023,0,0,0-1.851,1.226Z"
                              transform="translate(0 0)" fill="none" stroke="#000" stroke-linecap="round"
                              stroke-linejoin="round" stroke-width="1.5" />
                    </g>
                </svg>
            </a></li>
{{--        <li class="has-dropdown new">--}}
{{--            <a href="" class="nav-link"><svg xmlns="http://www.w3.org/2000/svg" width="23.148" height="26.56"--}}
{{--                                             viewBox="0 0 23.148 26.56">--}}
{{--                    <path id="Path_113" data-name="Path 113"--}}
{{--                          d="M22.962,22.748a1.1,1.1,0,0,1-.969.492H15.337a3.838,3.838,0,0,1-7.606,0H1.077a1.1,1.1,0,0,1-.969-.492,1.109,1.109,0,0,1,0-1.091l3.166-6.84V8.3A8.267,8.267,0,1,1,19.8,8.3v6.518l3.173,6.854A1.1,1.1,0,0,1,22.962,22.748ZM11.534,25.454a2.764,2.764,0,0,0,2.7-2.213h-5.4A2.764,2.764,0,0,0,11.534,25.454Zm7.217-10.281a.075.075,0,0,0,0-.009.576.576,0,0,1-.025-.1.566.566,0,0,1-.022-.111s0-.006,0-.009V8.3a7.165,7.165,0,1,0-14.33,0V14.94s0,.006,0,.009a.552.552,0,0,1-.022.111.571.571,0,0,1-.025.1s0,.007,0,.009L1.1,22.134H21.974Z"--}}
{{--                          transform="translate(0.039)" />--}}
{{--                </svg>--}}
{{--            </a>--}}
{{--            <div class="dropdown d-none">--}}
{{--                <ul>--}}
{{--                    <li><a href="#">Lorem ipsum dolor sit amet.</a></li>--}}
{{--                    <li><a href="#">Lorem, ipsum dolor.</a></li>--}}
{{--                </ul>--}}
{{--            </div>--}}
{{--        </li>--}}
        <li  class="has-dropdown user">
            <a onclick="this.nextElementSibling.classList.toggle('d-none'); return false;" class="nav-link" href="#">
                <?php
                $url = '';
                if(auth()->user()->upload) {
                    $url = auth()->user()->upload->file;
                    $url = CustomAsset('upload/full/'. $url);
                }else {
                    $url = 'https://ui-avatars.com/api/?background=fb4400&color=fff&name=' . auth()->user()->trans_name;
                }
                ?>
                <img style="width:40px;height:40px;object-fit:cover;border-radius: 50%;" src="{{$url}}" />

                <svg xmlns="http://www.w3.org/2000/svg" width="10.125" height="6.382" viewBox="0 0 10.125 6.382">
                    <path id="Path_114" data-name="Path 114" d="M6.382,5.063,0,0V10.125Z"
                          transform="translate(10.125) rotate(90)" fill="#363636" />
                </svg>
            </a>

            <div class="dropdown d-none">
                <ul>
                    <li><a href="{{route('user.info')}}"><i class="far fa-user"></i> <span class="mx-1">{{__('education.info')}}</span></a></li>
                    <li><a href="{{route('user.change_password')}}"><i class="fas fa-lock"></i> <span class="mx-1">{{__('education.Change Password')}}</a></li>
                    <li><a href="{{route('user.logout')}}"><i class="fas fa-sign-out-alt"></i> <span class="mx-1">{{__('education.Logout')}}</a></li>
                </ul>
            </div>
        </li>
    </ul>
</header>
