@section('style')
<style>
    .borderBottom {
        border-bottom: 2px solid #f51c40;
        position: absolute;
        /* top: 50%; */
        bottom: 0;
    }
</style>
@endsection
<header class="navbar navbar-dark sticky-top bg-white flex-md-nowrap p-0 shadow lms-header">
    <a class="navbar-brand col-md-3 col-lg-3 col-xl-2 me-0 px-3" href="{{CustomRoute('user.home')}}">
        <img src="{{CustomAsset('assets/images/logo.png')}}" alt="{{__('education.header_title')}}">
        <span class="d-none d-sm-block">BAKKAH<b>LMS</b></span>
    </a>
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
        <li  class="has-dropdown user">
            <a onclick="this.nextElementSibling.classList.toggle('d-none'); return false;" class="nav-link" href="#">
                <?php
                    $url = '';
                    if(auth()->user()->upload) {
                        // if ($url == ''){
                        //     $url = 'https://ui-avatars.com/api/?background=fb4400&color=fff&name=' . auth()->user()->trans_name;
                        // }else{
                            $url = auth()->user()->upload->file;
                            $url = CustomAsset('upload/full/'. $url);
                        // }
                    }else {
                        $url = 'https://ui-avatars.com/api/?background=23354b&color=fff&name=' . auth()->user()->trans_name;
                    }
                ?>
                <img style="width:40px;height:40px;object-fit:cover;border-radius: 50%;" src="{{$url}}" />

                <svg xmlns="http://www.w3.org/2000/svg" width="10.125" height="6.382" viewBox="0 0 10.125 6.382">
                    <path id="Path_114" data-name="Path 114" d="M6.382,5.063,0,0V10.125Z"
                          transform="translate(10.125) rotate(90)" fill="#363636" />
                </svg>
            </a>

            <div class="dropdown d-none">
                <ul class="postition-relative">
                    <li class="p-3 dropdown-item borderBottom" style="background: #f4f4f4; border-bottom: 1px solid gainsboro;">
                        <div>
                            <h2 style="font-size: 1.2rem;" class="mb-1">{{auth()->user()->trans_name}}</h2>
                            <small style="color: #73726c; font-weight:700;">{{$user_role_name}}</small>
                        </div>
                    </li>
                    <li><a href="{{route('user.info')}}"> <span class="mx-1">{{__('education.info')}}</span></a></li>
                    <li><a href="{{route('user.change_password')}}"> <span class="mx-1">{{__('education.Change Password')}}</a></li>
                    <li><a href="{{route('user.logout')}}"> <span class="mx-1">{{__('education.Logout')}}</a></li>
                </ul>
            </div>
        </li>
    </ul>
</header>
