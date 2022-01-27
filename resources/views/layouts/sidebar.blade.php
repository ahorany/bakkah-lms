<nav id="sidebarMenu" class="col-md-3 col-lg-3 col-xl-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">

        <?php
            $url = '';
            if(auth()->user()->upload) {
                // if (file_exists(auth()->user()->upload->file) == false){
                //     $url = 'https://ui-avatars.com/api/?background=fb4400&color=fff&name=' . auth()->user()->trans_name;
                // }else{
                    $url = auth()->user()->upload->file;
                    $url = CustomAsset('upload/full/'. $url);
                // }
            }else {
                $url = 'https://ui-avatars.com/api/?background=6a6a6a&color=fff&name=' . auth()->user()->trans_name;
            }
        ?>
        {{-- @if (file_exists($url)) --}}
        <div class="person-wrapper">
            <img src="{{$url}}" alt=" ">
            <h2 style="font-size: 20px; margin: 0;">{{auth()->user()->trans_name}}</h2>
            {{-- <medium style="color: #73726c; font-weight: 700; text-transform: capitalize;">{{$user_role_name}}</medium> --}}
            <hr>
        </div>
        {{-- @endif --}}

        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{url()->full() == CustomRoute('user.home')  ? 'active' : '' }}" aria-current="page" href="{{route('user.home')}}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36">
                        <path id="Path_132" data-name="Path 132"
                              d="M34.5,36h-12A1.5,1.5,0,0,1,21,34.5v-12A1.5,1.5,0,0,1,22.5,21h12A1.5,1.5,0,0,1,36,22.5v12A1.5,1.5,0,0,1,34.5,36Zm-12-13.5v12h12l0-12Zm12-7.5h-12A1.5,1.5,0,0,1,21,13.5V1.5A1.5,1.5,0,0,1,22.5,0h12A1.5,1.5,0,0,1,36,1.5v12A1.5,1.5,0,0,1,34.5,15ZM22.5,1.5v12h12l0-12ZM13.5,36H1.5A1.5,1.5,0,0,1,0,34.5v-12A1.5,1.5,0,0,1,1.5,21h12A1.5,1.5,0,0,1,15,22.5v12A1.5,1.5,0,0,1,13.5,36ZM1.5,22.5v12h12l0-12Zm12-7.5H1.5A1.5,1.5,0,0,1,0,13.5V1.5A1.5,1.5,0,0,1,1.5,0h12A1.5,1.5,0,0,1,15,1.5v12A1.5,1.5,0,0,1,13.5,15ZM1.5,1.5v12h12l0-12Z" />
                    </svg>
                    Dashboard
                </a>
            </li>

            @foreach($user_pages as $aside)
                <?php
                $has_treeview = is_null($aside->route_name) ? 'has-treeview' : '';
                $active = ($aside->id==session('infastructure_parent_id')) && url()->full() != CustomRoute('user.home') ? 'active' : '';
                $menu_open = $active=='active'?'menu-open':'';
                ?>

                <li class="nav-item {{$has_treeview}} {{$menu_open}}"><!--menu-open-->
                    {!!Builder::SidebarHref($aside, '#', $active)!!}
                    @if($has_treeview=='has-treeview')
                        <ul class="nav-treeview">
                            @foreach($user_pages_child as $infa_child)
                                @if ($infa_child->parent_id == $aside->id)
                                    <li class="nav-item">
                                        {!!Builder::SidebarHref($infa_child, null, '')!!}
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach

            @foreach($user_sidebar_courses->courses as $item)
                <li class="nav-item">
                    <a class="nav-link {{ (url()->full() == CustomRoute('user.course_details',$item->id)) && (url()->full() != CustomRoute('user.home'))  ? 'active' : '' }}" href="{{CustomRoute('user.course_details',$item->id) }}">
                        {{-- <svg version="1.1" id="courses" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                            viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve">
                        <style type="text/css">
                            .st0{fill:none;stroke:#000000;stroke-width:3.7582;stroke-miterlimit:10;}
                            .st1{fill:none;stroke:#000000;stroke-width:3.7582;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;}
                        </style>
                        <path class="st0" d="M76.86,83.73H24.15c-5.58,0-10.11-4.53-10.11-10.11V25.37c0-5.58,4.53-10.11,10.11-10.11h52.71
                            c5.58,0,10.11,4.53,10.11,10.11v48.25C86.97,79.2,82.44,83.73,76.86,83.73z"/>
                        <line class="st1" x1="73.2" y1="36.25" x2="29.34" y2="36.25"/>
                        <line class="st1" x1="64.64" y1="46.17" x2="36.37" y2="46.17"/>
                        <line class="st1" x1="73.2" y1="56.08" x2="29.34" y2="56.08"/>
                        <line class="st1" x1="73.2" y1="66" x2="29.34" y2="66"/>
                        </svg> --}}
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" xml:space="preserve">
                            <style type="text/css">
                                .st0{fill:none;stroke:#fff;stroke-width:3.0215;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:22.9256;}
                                .st1{fill:none;stroke:#fff;stroke-width:3.0221;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:22.9256;}
                            </style>
                            <g>
                                <polyline class="st0" points="81.49,25.18 86.65,25.18 86.65,70.19 12.29,70.19 12.29,25.18 17.45,25.18 	"></polyline>
                                <path class="st1" d="M31.38,83.94h36.19 M43.28,82.59V72.01 M55.66,82.59V72.01 M26.35,24.55c9.22,0,10.82,0,15.53,0 M26.35,32.64
                                    c9.22,0,10.82,0,15.53,0 M26.35,40.74c9.22,0,10.82,0,15.53,0 M26.35,48.84c9.22,0,10.82,0,15.53,0 M18.96,16.33
                                    c6.84,0,19.8,0,26.63,0l3.69,4.02c0,13.87,0,27.04,0,40.91l-3.69-4.02c-6.84,0-19.8,0-26.63,0C18.96,43.37,18.96,30.2,18.96,16.33
                                    L18.96,16.33z M53.35,16.33l-3.69,4.02 M49.66,61.27l3.69-4.02 M53.35,57.25c6.84,0,19.79,0,26.63,0c0-13.87,0-27.04,0-40.91
                                    c-6.84,0-19.79,0-26.63,0"></path>
                                <polyline class="st0" points="79.99,57.77 79.99,63.65 18.95,63.65 18.95,57.64 	"></polyline>
                                <path class="st1" d="M57.05,24.55c9.22,0,10.82,0,15.53,0 M57.05,32.64c9.22,0,10.82,0,15.53,0 M57.05,40.74
                                    c9.22,0,10.82,0,15.53,0"></path>
                            </g>
                        </svg>

                        {{$item->trans_title}}
                    </a>
                </li>
            @endforeach

        </ul>
    </div>
</nav>
