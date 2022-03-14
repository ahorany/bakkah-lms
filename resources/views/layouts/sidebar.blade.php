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

        <ul class="nav flex-column postition-relative">

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

            <li class="mobile-show">
                <div class="dropdown-sidebar">
                    <a href="#">
                        <span>Roles</span>
                    </a>
                    <ul>
                        <?php $role_id = $role->id; ?>
                        @foreach(\App\Models\Training\Role::select('id','name','icon')->get() as $role)
                            <li class="nav-item" @if($role->id == $role_id) style="background: #eee;" @endif>
                                <a class="nav-link {{  session()->get('active_sidebar_route_name') == $user_page->route_name ? 'active' : '' }}" href="{{route('user.change.role',$role->id)}}">
                                    <span class="d-flex">
                                        <img class="svg-icons svg-icons-h" src="{{CustomAsset('icons/'.$role->icon)}}" alt="{{__('education.roles')}}"/>
                                        {{$role->name}}
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </li>

            <li class="mobile-show">
                <div class="dropdown-sidebar">
                    <a href="#">
                        <span>Notifications</span>
                    </a>
                    <ul>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('user.add_message')}}">
                                <span class="d-flex">
                                    <img class="svg-icons svg-icons-h" src="{{CustomAsset('icons/send-msg.svg')}}" alt="{{__('education.Send Message')}}"/>
                                    {{__('education.Send Message')}}
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('user.messages.inbox',['type'=>'sent'])}}">
                                <span class="d-flex">
                                    <img class="svg-icons svg-icons-h" src="{{CustomAsset('icons/sent-item.svg')}}" alt="{{__('education.Sent Items')}}"/>
                                    {{__('education.Sent Items')}}
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('user.messages.inbox',['type'=>'inbox'])}}">
                                <span class="d-flex">
                                    <img class="svg-icons svg-icons-h" src="{{CustomAsset('icons/inbox.svg')}}" alt="{{__('education.Inbox')}}"/>
                                    {{__('education.Inbox')}}
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="mobile-show">
                <div class="dropdown-sidebar">
                    <a href="#">
                        <span>Settings</span>
                    </a>
                     <ul>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('user.info')}}">
                                <span class="d-flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve">
                                        <style type="text/css">
                                            .st0{fill:#FFFFFF;}
                                        </style>
                                        <g>
                                            <path d="M96.47,46.82c0,2.17,0,4.35,0,6.52c-0.2,1.49-0.36,2.98-0.62,4.46c-1.36,7.91-4.49,15.04-9.55,21.28   c-7.27,8.96-16.59,14.55-27.96,16.66c-1.65,0.31-3.33,0.49-4.99,0.73c-2.17,0-4.35,0-6.52,0c-0.26-0.06-0.52-0.13-0.79-0.17   c-2.32-0.37-4.67-0.59-6.94-1.14C15.97,89.57,0.75,67.14,4.11,43.64c1.19-8.32,4.27-15.89,9.64-22.38   C25.24,7.39,40.03,1.77,57.81,4.33c8,1.15,15.15,4.51,21.42,9.64c8.9,7.29,14.46,16.6,16.52,27.95   C96.04,43.54,96.23,45.18,96.47,46.82z M10.22,50.03c-0.02,22.01,17.83,39.9,39.82,39.93c22,0.03,39.9-17.83,39.92-39.83   c0.02-22.01-17.82-39.9-39.82-39.93C28.13,10.18,10.24,28.03,10.22,50.03z"/>
                                            <path d="M45.38,56.78c0-3.59,0-7.19,0-10.78c0-1.98,0.63-2.83,2.55-3.37c1.77-0.5,3.52-0.42,5.21,0.35   c1.14,0.52,1.74,1.37,1.74,2.7c-0.03,7.46-0.02,14.92,0,22.38c0,1.04-0.39,1.82-1.25,2.35c-2.21,1.37-4.49,1.43-6.78,0.2   c-0.99-0.54-1.5-1.37-1.49-2.58C45.41,64.27,45.38,60.53,45.38,56.78z"/>
                                            <path d="M55.19,32.38c-0.23,2.52-2.7,4.57-5.35,4.38c-1.58-0.11-2.91-0.71-3.91-1.97c-1.12-1.42-1.17-3.21-0.14-4.69   c1.98-2.85,6.74-2.8,8.58,0.14C54.78,30.87,54.93,31.66,55.19,32.38z"/>
                                        </g>
                                    </svg>
                                    {{__('education.info')}}
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('user.certificate')}}">
                                <span class="d-flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 36">
                                        <path id="Path_132" data-name="Path 132"
                                              d="M34.5,36h-12A1.5,1.5,0,0,1,21,34.5v-12A1.5,1.5,0,0,1,22.5,21h12A1.5,1.5,0,0,1,36,22.5v12A1.5,1.5,0,0,1,34.5,36Zm-12-13.5v12h12l0-12Zm12-7.5h-12A1.5,1.5,0,0,1,21,13.5V1.5A1.5,1.5,0,0,1,22.5,0h12A1.5,1.5,0,0,1,36,1.5v12A1.5,1.5,0,0,1,34.5,15ZM22.5,1.5v12h12l0-12ZM13.5,36H1.5A1.5,1.5,0,0,1,0,34.5v-12A1.5,1.5,0,0,1,1.5,21h12A1.5,1.5,0,0,1,15,22.5v12A1.5,1.5,0,0,1,13.5,36ZM1.5,22.5v12h12l0-12Zm12-7.5H1.5A1.5,1.5,0,0,1,0,13.5V1.5A1.5,1.5,0,0,1,1.5,0h12A1.5,1.5,0,0,1,15,1.5v12A1.5,1.5,0,0,1,13.5,15ZM1.5,1.5v12h12l0-12Z" />
                                    </svg>
                                    {{__('education.Certificates')}}
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('user.change_password')}}">
                                <span class="d-flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 100 100" xml:space="preserve">
                                        <g>
                                            <path d="M51.34,6.77c1.51,0.22,3.02,0.41,4.52,0.66c6.68,1.13,12.72,3.76,18.18,7.75c0.16,0.11,0.32,0.22,0.65,0.45   c0-1.17-0.02-2.19,0.01-3.22c0.01-0.51,0.03-1.03,0.16-1.51c0.32-1.14,1.38-1.84,2.59-1.77c1.14,0.06,2.09,0.86,2.33,2.01   c0.07,0.36,0.1,0.73,0.1,1.1c0.01,2.81,0.01,5.62,0,8.42c0,2.16-0.69,2.95-2.8,3.32c-2.98,0.52-5.96,1.06-8.94,1.59   c-1.41,0.25-2.73-0.6-3.02-1.96c-0.3-1.39,0.49-2.73,1.9-3.04c1.24-0.28,2.5-0.46,3.75-0.69c0.19-0.03,0.37-0.1,0.84-0.24   c-1.82-1.13-3.39-2.23-5.07-3.13c-6.49-3.49-13.43-5.15-20.78-4.47C29.64,13.52,18.5,21.9,12.34,36.81   c-2.12,5.12-2.87,10.54-2.38,16.07c1.27,14.59,8.51,25.25,21.45,31.97c6.05,3.14,12.59,4.27,19.42,3.84   c15.64-0.97,29.36-11.7,34.13-26.82c1.18-3.76,1.72-7.61,1.8-11.54c0.03-1.33,0.95-2.38,2.18-2.53c1.29-0.16,2.44,0.57,2.81,1.82   c0.09,0.29,0.11,0.61,0.1,0.92c-0.07,10.32-3.34,19.52-9.9,27.48c-6.66,8.08-15.14,13.32-25.46,15.12   c-15.65,2.73-29.15-1.67-40.17-13.19c-5.99-6.27-9.56-13.82-11.05-22.35c-0.23-1.33-0.38-2.68-0.57-4.01c0-2.16,0-4.31,0-6.47   c0.19-1.34,0.34-2.68,0.57-4.01c1.51-8.68,5.23-16.27,11.3-22.66c6.85-7.22,15.2-11.65,25.05-13.17c1.14-0.18,2.28-0.33,3.42-0.5   C47.14,6.77,49.24,6.77,51.34,6.77z"/>
                                            <path d="M48.23,71.62c-4.99,0-9.98,0-14.97,0c-1.84,0-2.85-0.99-2.85-2.81c0-6.92,0-13.84,0-20.76c0-1.79,1-2.81,2.77-2.81   c10.07,0,20.14,0,30.2,0c1.76,0,2.77,1.02,2.77,2.81c0,6.92,0,13.84,0,20.76c0,1.82-1.01,2.81-2.85,2.81   C58.27,71.62,53.25,71.62,48.23,71.62z M48.24,59.7c0.59,0,1.19,0.03,1.78-0.01c1.39-0.09,2.48-1.19,2.5-2.51   c0.03-1.3-1.05-2.49-2.44-2.57c-1.21-0.07-2.43-0.07-3.64,0c-1.38,0.08-2.46,1.28-2.42,2.58c0.04,1.32,1.12,2.41,2.52,2.49   C47.11,59.73,47.68,59.7,48.24,59.7z"/>
                                            <path d="M39.77,40.09c-1.72,0-3.35,0-4.99,0c-1.02-7.85,1.65-15.33,10.7-17.53c7.43-1.81,15.26,3.61,16.21,11.18   c0.26,2.06,0.04,4.18,0.04,6.34c-1.52,0-3.18,0-4.94,0c0-1.34,0.02-2.69,0-4.04c-0.06-3.97-2.52-7.27-6.19-8.31   c-5.38-1.53-10.66,2.42-10.82,8.11C39.73,37.22,39.77,38.64,39.77,40.09z"/>
                                        </g>
                                    </svg>
                                    {{__('education.Change Password')}}
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('user.logout')}}">
                                <span class="d-flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve">
                                        <style type="text/css">
                                            .st0{fill:#FFFFFF;}
                                        </style>
                                        <g>
                                            <path d="M65.16,8c0.19,0.1,0.38,0.2,0.57,0.28c1.83,0.78,2.8,2.64,2.39,4.6c-0.37,1.78-2,3.07-3.91,3.07c-12.38,0-24.75,0-37.13,0   c-2.67,0-4.71,1.42-5.4,3.79c-0.17,0.6-0.26,1.25-0.26,1.87c-0.01,19.2-0.01,38.39-0.01,57.59c0,3.44,2.27,5.69,5.73,5.69   c12.35,0,24.7,0,37.04,0c1.62,0,2.83,0.72,3.56,2.13c0.73,1.41,0.64,2.84-0.28,4.15c-0.76,1.08-1.82,1.67-3.16,1.67   c-12.73,0-25.47,0.03-38.2-0.01c-6.02-0.02-11.72-5.16-12.48-11.13c-0.11-0.85-0.17-1.7-0.17-2.56   c-0.01-19.17,0.05-38.34-0.04-57.5c-0.03-7.07,5.14-12.68,11.34-13.51c0.1-0.01,0.19-0.09,0.29-0.14C38.42,8,51.79,8,65.16,8z"/>
                                            <path d="M73.69,46.45c-0.31-0.34-0.51-0.56-0.71-0.76c-3.75-3.75-7.51-7.49-11.24-11.25c-2.26-2.28-1.4-5.9,1.59-6.76   c1.46-0.42,2.79-0.1,3.9,0.97c0.84,0.8,1.65,1.63,2.47,2.45c5.37,5.37,10.74,10.74,16.11,16.11c2.11,2.11,2.12,4.33,0.02,6.42   c-6.09,6.09-12.18,12.19-18.28,18.27c-1.91,1.9-4.63,1.88-6.18-0.04c-1.34-1.65-1.17-3.91,0.44-5.53   c3.53-3.54,7.06-7.07,10.61-10.6c0.39-0.39,0.82-0.73,1.24-1.09c-0.04-0.08-0.07-0.16-0.11-0.24c-0.28,0-0.57,0-0.85,0   c-10.05,0-20.11,0-30.16,0c-2.08,0-3.8-1.46-4.04-3.41c-0.26-2.06,0.99-3.91,3.02-4.43c0.39-0.1,0.82-0.1,1.23-0.11   c9.97-0.01,19.94,0,29.91,0C72.94,46.45,73.23,46.45,73.69,46.45z"/>
                                        </g>
                                    </svg>
                                    {{__('education.Logout')}}
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</nav>


