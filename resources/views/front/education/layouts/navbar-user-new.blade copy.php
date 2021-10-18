<div class="menu-overlay"></div>
<nav id="navbar" class="navbar navbar-expand-xl navbar-light bg-white p-0">
    <div class="container-fluid">
        <span itemprop="headline">
            <a class="navbar-brand" href="{{route('education.index')}}" title="{{__('education.header_title')}}">
                <img src="{{CustomAsset('images/logo.png')}}" alt="{{__('education.header_title')}}">
            </a>
        </span>
        {{-- <a href="{{route('education.courses')}}" class="btn btn-primary btn-sm d-xl-none">{{__('education.explore_all_courses')}}</a> --}}
        {{-- <a class="btn btn-primary px-3 btn-sm d-xl-none" href="{{route('education.hot-deals')}}">{{__('education.Hot Deals')}}</a> --}}
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse user_header" id="navbarSupportedContent">
            <div class="d-flex">
                <div id="search-wrapper">
                    <autocomplete />
                </div>
            </div>

            <ul class="navbar-nav ml-auto">

                @auth
                    <li id="user" class="nav-item dropdown d-flex">
                        <a class="nav-link dropdown-toggle cart-item p-0" href="{{route('user.my_courses')}}">
                        <div class="cart-wrapper profile-img">
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
                        </div>
                        </a>
                        <div class="nav-item mx-2 profile-info">
                            <p class="m-0 username">{{auth()->user()->trans_name}}</p>
                            <p class="m-0 email">{{auth()->user()->email}}</p>
                        </div>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a style="background: #f8f9fa" class="nav-link dropdown-item">
                                <span class="d-block py-2 main-color">{{__('education.Welcome,')}} {{ auth()->user()->trans_name }}</span>
                            </a>
                            <a class="nav-link dropdown-item {{\Request::url()==route('user.home')?'active':''}}" href="{{route('user.home')}}"><i class="far fa-user"></i> <span class="mx-1">{{__('education.home')}}</span></a>
                            <a class="nav-link dropdown-item" href="{{route('user.change_password')}}"><i class="fas fa-lock"></i> <span class="mx-1">{{__('education.Change Password')}}</span></a>
                        </div>
                    </li>
                @endauth



                <li class="nav-item">
                    <a class="nav-link" href="{{route('user.logout')}}"><i style="color: #a3a3a3;" class="fas fa-sign-out-alt"></i> <span class="mx-1 logout">{{__('education.Logout')}}</span></a>
                </li>

            </ul>
        </div>
    </div>
</nav> <!-- /#navbar -->

