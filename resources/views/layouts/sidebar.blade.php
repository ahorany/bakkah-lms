<nav id="sidebarMenu" class="col-md-3 col-lg-3 col-xl-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">
        <?php
            $url = '';
            if(auth()->user()->upload) {
                    $url = auth()->user()->upload->file;
                    $url = CustomAsset('upload/full/'. $url);
            }else {
                $url = 'https://ui-avatars.com/api/?background=6a6a6a&color=fff&name=' . auth()->user()->trans_name;
            }
        ?>

        <div class="person-wrapper">
            <img src="{{$url}}" alt=" ">
            <h2 style="font-size: 20px; margin: 0;">{{auth()->user()->trans_name}}</h2>
            <hr>
        </div>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{  session()->get('active_sidebar_route_name') == 'user.home' ? 'active' : '' }}" aria-current="page" href="{{route('user.home')}}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36">
                        <path id="Path_132" data-name="Path 132"
                              d="M34.5,36h-12A1.5,1.5,0,0,1,21,34.5v-12A1.5,1.5,0,0,1,22.5,21h12A1.5,1.5,0,0,1,36,22.5v12A1.5,1.5,0,0,1,34.5,36Zm-12-13.5v12h12l0-12Zm12-7.5h-12A1.5,1.5,0,0,1,21,13.5V1.5A1.5,1.5,0,0,1,22.5,0h12A1.5,1.5,0,0,1,36,1.5v12A1.5,1.5,0,0,1,34.5,15ZM22.5,1.5v12h12l0-12ZM13.5,36H1.5A1.5,1.5,0,0,1,0,34.5v-12A1.5,1.5,0,0,1,1.5,21h12A1.5,1.5,0,0,1,15,22.5v12A1.5,1.5,0,0,1,13.5,36ZM1.5,22.5v12h12l0-12Zm12-7.5H1.5A1.5,1.5,0,0,1,0,13.5V1.5A1.5,1.5,0,0,1,1.5,0h12A1.5,1.5,0,0,1,15,1.5v12A1.5,1.5,0,0,1,13.5,15ZM1.5,1.5v12h12l0-12Z" />
                    </svg>
                    Dashboard
                </a>
            </li>



        @if(!auth()->user()->hasRole(['Admin']))
            @foreach($user_sidebar_courses->courses as $item)
                <li class="nav-item">
                    <a class="nav-link {{ ( session()->get('active_sidebar_route_name') == -1) &&  (url()->full() == CustomRoute('user.course_details',$item->id)) && (url()->full() != CustomRoute('user.home'))  ? 'active' : '' }}" href="{{CustomRoute('user.course_details',$item->id) }}">
                        <span class="d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="36" height="36" viewBox="0 0 24.567 23.684">
                                <defs>
                                <clipPath id="clip-path">
                                    <path id="Path_79" data-name="Path 79" d="M5.872-23.684a.527.527,0,0,0-.528.528h0v10.488H.527A.526.526,0,0,0,0-12.141H0V-3.2A3.2,3.2,0,0,0,3.2,0H21.367a3.2,3.2,0,0,0,3.2-3.2h0V-23.156a.527.527,0,0,0-.528-.528H5.872ZM6.4-3.2v-19.43H23.512V-3.2a2.148,2.148,0,0,1-2.145,2.144H5.573A3.185,3.185,0,0,0,6.4-3.2m-5.344,0v-8.415H5.344V-3.2A2.147,2.147,0,0,1,3.2-1.055h0A2.146,2.146,0,0,1,1.056-3.2m11.181-17.69L9.722-18.134l-.783-.829a.529.529,0,0,0-.746-.022h0a.528.528,0,0,0-.021.747h0L9.345-17a.526.526,0,0,0,.384.165h0A.526.526,0,0,0,10.119-17h0l2.9-3.173a.526.526,0,0,0-.034-.745h0a.523.523,0,0,0-.356-.138h0a.527.527,0,0,0-.39.171m1.978,2.244a.527.527,0,0,0-.528.527h0a.527.527,0,0,0,.528.528h6.763a.527.527,0,0,0,.527-.528h0a.526.526,0,0,0-.527-.527H14.215Zm-1.978,3.8L9.722-12.087l-.783-.829a.529.529,0,0,0-.746-.022h0a.528.528,0,0,0-.021.747h0l1.173,1.242a.529.529,0,0,0,.384.165h0a.533.533,0,0,0,.386-.172h0l2.9-3.174a.526.526,0,0,0-.034-.745h0a.526.526,0,0,0-.355-.138h0a.531.531,0,0,0-.391.172m1.978,1.991a.528.528,0,0,0-.528.527h0a.527.527,0,0,0,.528.528h6.763a.527.527,0,0,0,.527-.528h0a.527.527,0,0,0-.527-.527H14.215ZM12.237-8.794,9.722-6.04l-.783-.829a.528.528,0,0,0-.746-.02h0a.526.526,0,0,0-.021.745h0L9.345-4.9a.529.529,0,0,0,.384.165h0a.53.53,0,0,0,.386-.172h0l2.9-3.173a.527.527,0,0,0-.034-.746h0a.526.526,0,0,0-.355-.138h0a.531.531,0,0,0-.391.172m1.978,1.74a.527.527,0,0,0-.528.527h0A.527.527,0,0,0,14.215-6h6.763a.527.527,0,0,0,.527-.528h0a.526.526,0,0,0-.527-.527H14.215Z" fill="#bdbdbd"/>
                                </clipPath>
                                </defs>
                                <g id="Group_57" data-name="Group 57" transform="translate(0 23.684)">
                                <g id="Group_56" data-name="Group 56" clip-path="url(#clip-path)">
                                    <g id="Group_55" data-name="Group 55" transform="translate(12.284 -11.842)">
                                    <path id="Path_78" data-name="Path 78" d="M-12.284-11.842H12.284V11.842H-12.284Z" fill="#bdbdbd"/>
                                    </g>
                                </g>
                                </g>
                            </svg>
                        </span>
                        <span>{{$item->trans_title}}</span>
                    </a>
                </li>
            @endforeach
        @endif



        @foreach($user_pages as $user_page)
                @can($user_page->route_name)
                    <?php
                        $icon_content = '';
                        if (file_exists(public_path('icons/sidebar/'.$user_page->icon))){
                            $icon_content = file_get_contents(public_path('icons/sidebar/'.$user_page->icon));
                        }
                    ?>

                    <li class="nav-item ">
                        <a class="nav-link {{  session()->get('active_sidebar_route_name') == $user_page->route_name ? 'active' : '' }}" href="{{CustomRoute($user_page->route_name) }}">
                        <span class="d-flex align-items-center">{!! $icon_content !!}</span>
                            <span>{{$user_page->trans_title}}</span>
                        </a>
                    </li>
                @endcan
        @endforeach

        </ul>
    </div>
</nav>
