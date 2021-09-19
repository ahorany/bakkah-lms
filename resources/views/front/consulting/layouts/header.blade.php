<header class="consulting-header sticky-top">
    <div class="menu-overlay"></div>
    <nav id="navbar" class="navbar navbar-expand-xl p-0">
        <div class="container-fluid">
            <h1 itemprop="headline">
                <a class="navbar-brand" href="{{route('consulting.index')}}" title="{{__('consulting.header_title')}}">
                    <img src="{{CustomAsset('images/logo.png')}}" alt="{{__('consulting.DC_title')}}" title="{{__('consulting.header_title')}}">
                </a>
            </h1>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mx-5 mr-auto align-items-center">
                    @foreach($consulting_navbar_menus as $consulting_navbar_menu)
                        <?php
                        $args = [];
                        if(!is_null($consulting_navbar_menu->route_param)){
                            $args = array_merge($args, json_decode($consulting_navbar_menu->route_param, true));
                        }
                        if($consulting_navbar_menu->post_type == 'careers') { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="https://www.menalite.com/menaitech/onlineapp/onlineapplication/bakkah/hq/1">{{$consulting_navbar_menu->trans_title}}</a>
                        </li>
                        <?php }else { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route($consulting_navbar_menu->route_name, $args)}}">{{$consulting_navbar_menu->trans_title}}</a>
                        </li>
                        <?php } ?>
                    @endforeach
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="{{route('consulting.static.knowledge-hub')}}" id="navbarDropdown">
                            {{__('consulting.Knowledge Hub')}}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="nav-link dropdown-item {{\Request::url()==route('consulting.static.knowledge-center', ['post_type'=>'knowledge-center'])?'active':''}}" href="{{route('consulting.static.knowledge-center', ['post_type'=>'knowledge-center'])}}">{{__('consulting.Knowledge Center')}}</a>
                            <a class="nav-link dropdown-item {{\Request::url()==route('consulting.static.webinars')?'active':''}}" href="{{route('consulting.static.webinars')}}">{{__('consulting.webinars')}}</a>
                            <a class="nav-link dropdown-item {{\Request::url()==route('consulting.static.reports')?'active':''}}" href="{{route('consulting.static.reports')}}">{{__('consulting.Reports')}}</a>
                        </div>
                      </li>
                    <!--<li class="nav-item">
                        <a class="nav-link" href="#">Career</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Company</a>
                    </li>-->
                </ul>

                <ul class="navbar-nav ml-auto">
                    <!-- d-none: ahorany -->
                    <li class="nav-item d-none">
                        <a class="nav-link btn-login" href="#">
                            <i class="fas fa-user"></i> Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('consulting.static.contactusIndex')}}">{{__('consulting.Contact Us')}}</a>
                        {{--<a class="nav-link btn btn-sm btn-table" href="{{CustomRoute('consulting.static.contactusIndex')}}">{{__('consulting.Request Proposal')}}</a>--}}
                    </li>
                </ul>
                @include(FRONT.'.Html.switch-lang')
            </div>
        </div>
    </nav> <!-- /#navbar -->

    <div class="progress-container">
        <div class="progress-bar" id="myBar"></div>
    </div>
</header>
