<nav id="sidebarMenu" class="col-md-3 col-lg-3 col-xl-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">
        <?php
            $url = '';
            if(auth()->user()->upload) {
                    $url = auth()->user()->upload->file;
                    $url = CustomAsset('upload/full/'. $url);
            }else {
                $url = 'https://ui-avatars.com/api/?background=6a6a6a&color=fff&name=' . getCurrentUserBranchData()->name;
            }
        ?>

        <div class="person-wrapper">
            <img src="{{$url}}" alt=" ">
            <h2 style="font-size: 20px; margin: 0;">{{getCurrentUserBranchData()->name}}</h2>
            <hr>
        </div>

        <ul class="nav flex-column postition-relative">

            <li class="nav-item">
                <a class="nav-link {{  session()->get('active_sidebar_route_name') == 'user.home' ? 'active' : '' }}" aria-current="page" href="{{route('user.home')}}">
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36">
                            <path id="Path_132" data-name="Path 132"
                                  d="M34.5,36h-12A1.5,1.5,0,0,1,21,34.5v-12A1.5,1.5,0,0,1,22.5,21h12A1.5,1.5,0,0,1,36,22.5v12A1.5,1.5,0,0,1,34.5,36Zm-12-13.5v12h12l0-12Zm12-7.5h-12A1.5,1.5,0,0,1,21,13.5V1.5A1.5,1.5,0,0,1,22.5,0h12A1.5,1.5,0,0,1,36,1.5v12A1.5,1.5,0,0,1,34.5,15ZM22.5,1.5v12h12l0-12ZM13.5,36H1.5A1.5,1.5,0,0,1,0,34.5v-12A1.5,1.5,0,0,1,1.5,21h12A1.5,1.5,0,0,1,15,22.5v12A1.5,1.5,0,0,1,13.5,36ZM1.5,22.5v12h12l0-12Zm12-7.5H1.5A1.5,1.5,0,0,1,0,13.5V1.5A1.5,1.5,0,0,1,1.5,0h12A1.5,1.5,0,0,1,15,1.5v12A1.5,1.5,0,0,1,13.5,15ZM1.5,1.5v12h12l0-12Z" />
                        </svg>
                    </span>
                    <span>Dashboard</span>
                </a>
            </li>

          @isset($role->id)
              @if($role->role_type_id != 510)
                    @foreach($user_sidebar_courses as $item)
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
                            <span>{{\App\Helpers\Lang::TransTitle($item->title)}}</span>
                        </a>
                    </li>
                   @endforeach
              @endif
          @endisset


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

            @if ($role->role_type_id == 510  || getCurrentUserBranchData()->delegation_role_id != null)
                <li class="mobile-show">

                        <div class="dropdown-sidebar">
                         <a href="#">
                            <span class="title">
                                <span>
                                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve">
                                        <g>
                                            <path d="M4.81,71.7c0.23-1.02,0.41-2.06,0.7-3.07c2.13-7.55,9.06-12.93,16.89-12.97c10.85-0.05,21.7-0.07,32.55,0
                                                c9.65,0.06,17.51,7.97,17.61,17.63c0.05,4.41,0.02,8.82,0.01,13.23c0,1.82-1.17,3-2.98,3c-20.58,0.01-41.17,0-61.75,0.01
                                                c-1.54,0-2.42-0.84-3.02-2.12C4.81,82.17,4.81,76.93,4.81,71.7z M66.87,83.83c0-3.87,0.27-7.71-0.06-11.5
                                                c-0.54-6.27-5.67-10.99-11.73-11.02c-10.94-0.06-21.87-0.06-32.81,0c-5.78,0.03-10.97,4.61-11.61,10.35
                                                c-0.33,2.99-0.16,6.04-0.2,9.07c-0.02,1.02,0,2.04,0,3.1C29.29,83.83,48.04,83.83,66.87,83.83z"/>
                                            <path d="M38.67,48.1c-10.36-0.01-18.8-8.43-18.82-18.78c-0.02-10.37,8.47-18.85,18.85-18.85C49.08,10.48,57.57,19,57.52,29.35
                                                C57.46,39.73,49.04,48.11,38.67,48.1z M38.64,42.45c7.28,0.02,13.22-5.87,13.23-13.14c0.01-7.22-5.89-13.16-13.1-13.19
                                                c-7.28-0.03-13.22,5.84-13.27,13.11C25.45,36.48,31.37,42.43,38.64,42.45z"/>
                                            <path d="M85.46,49.07c-0.55,0-0.87,0-1.19,0c-7.85,0-15.7,0-23.54,0c-2.02,0-3.29-1.23-3.19-3.04c0.08-1.34,1.12-2.44,2.46-2.57
                                                c0.38-0.04,0.76-0.03,1.15-0.03c7.73,0,15.46,0,23.19,0c0.31,0,0.62,0,0.93,0c0.1-0.13,0.2-0.26,0.3-0.39
                                                c-0.29-0.15-0.64-0.23-0.86-0.45c-3.11-3.08-6.2-6.19-9.3-9.28c-0.9-0.9-1.18-1.95-0.75-3.14c0.4-1.1,1.25-1.69,2.4-1.79
                                                c1.01-0.09,1.79,0.38,2.49,1.08c4.56,4.58,9.14,9.15,13.71,13.72c0.35,0.35,0.73,0.68,1.07,1.04c1.13,1.2,1.15,2.88-0.01,4.04
                                                c-4.98,4.99-9.98,9.98-14.98,14.95c-1.23,1.22-2.96,1.21-4.09,0.06c-1.11-1.14-1.06-2.86,0.16-4.1c3.07-3.09,6.15-6.16,9.22-9.23
                                                C84.87,49.7,85.08,49.47,85.46,49.07z"/>
                                        </g>
                                    </svg>
                                </span>
                                <span>Permissions</span>
                            </span>
                            <span class="drop-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24.37" height="16.233" viewBox="0 0 24.37 16.233">
                                    <path id="Line_Arrow_Right" data-name="Line Arrow Right" d="M3.967,0,0,4.048,12.185,16.233,24.37,4.048,20.4,0,12.185,8.3Z"/>
                                </svg>
                            </span>
                        </a>
                        <ul>
                            <?php $role_id = $role->id; ?>
                                @foreach($headerRoles as $role)
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
            @endif
            <li class="mobile-show">
                <div class="dropdown-sidebar">
                    <a href="#">
                        <span class="title">
                            <span>
                                <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve">
                                    <g>
                                        <path d="M51.11,9.62c1.29,0.51,2.7,0.83,3.86,1.56c3.04,1.9,4.33,5.15,3.85,9.16c0.16,0.08,0.34,0.18,0.53,0.26
                                            c9.79,4.31,15.39,11.77,16.76,22.38c0.13,1.02,0.14,2.06,0.15,3.09c0.01,4.8,0.01,9.59,0.01,14.39c0,1.4,0.18,1.6,1.56,1.71
                                            c4.45,0.37,7.99,3.34,8.77,7.37c1.12,5.69-3.15,10.96-8.95,11c-4.98,0.03-9.96,0.01-14.95,0.01c-0.29,0-0.58,0-0.92,0
                                            c-0.52,3.5-2.14,6.32-5.01,8.36c-2.15,1.52-4.55,2.2-7.18,2.14c-4.9-0.11-10.49-3.88-11.39-10.5c-0.28,0-0.56,0-0.85,0
                                            c-4.93,0-9.86,0.01-14.79,0c-5.44-0.01-9.52-4.29-9.31-9.71c0.17-4.22,3.75-8.04,8.03-8.56c0.32-0.04,0.63-0.07,0.95-0.09
                                            c1.31-0.11,1.51-0.33,1.51-1.68c0-5.11-0.1-10.23,0.02-15.34c0.27-11.32,5.67-19.34,15.86-24.19c0.5-0.24,1.19-0.33,1.44-0.71
                                            c0.24-0.35-0.01-1.02-0.02-1.54c-0.08-4.39,3.07-8.18,7.39-8.93c0.15-0.03,0.29-0.1,0.44-0.15C49.63,9.62,50.37,9.62,51.11,9.62z
                                            M50,75.3c9.14,0,18.29,0.01,27.43-0.01c2.2,0,3.92-1.54,4.06-3.57c0.16-2.16-1.29-3.92-3.53-4.25c-0.63-0.09-1.27-0.08-1.89-0.2
                                            c-3-0.62-5.05-3.14-5.06-6.26c-0.02-5.04-0.02-10.07,0-15.11c0.01-2.79-0.47-5.48-1.46-8.08c-4.51-11.81-17.85-17.21-28.96-11.5
                                            c-7.39,3.8-11.31,10.05-11.56,18.4c-0.16,5.43-0.02,10.87-0.04,16.3c-0.01,3.12-2.06,5.64-5.07,6.25c-0.7,0.14-1.43,0.12-2.13,0.24
                                            c-1.71,0.3-3.11,1.75-3.28,3.35c-0.2,1.79,0.72,3.47,2.36,4.09c0.65,0.25,1.39,0.33,2.09,0.33C31.98,75.31,40.99,75.3,50,75.3z
                                            M56.34,80.59c-4.23,0-8.45,0-12.68,0c0.25,2.73,3.21,5.18,6.25,5.21C53.04,85.83,56.05,83.41,56.34,80.59z M53.61,18.95
                                            c0.23-1.39-0.45-2.8-1.7-3.54c-1.32-0.79-2.96-0.69-4.18,0.24c-1.1,0.85-1.63,2.23-1.33,3.3C48.81,18.95,51.18,18.95,53.61,18.95z"
                                            />
                                        <path d="M15.97,41.13c-1.79,0.01-3.02-1.55-2.49-3.39c0.67-2.33,1.39-4.67,2.33-6.9c1.68-3.99,4.2-7.46,7.24-10.55
                                            c0.83-0.84,1.8-1.11,2.9-0.71c1.05,0.38,1.6,1.19,1.69,2.29c0.08,0.98-0.43,1.7-1.08,2.37c-3.96,4.05-6.58,8.85-7.9,14.36
                                            c-0.06,0.26-0.12,0.52-0.2,0.77C18.08,40.44,17.11,41.12,15.97,41.13z"/>
                                        <path d="M86.62,38.37c0.01,1.43-0.94,2.56-2.2,2.74c-1.33,0.19-2.52-0.57-2.93-1.89c-0.48-1.57-0.87-3.17-1.45-4.7
                                            c-1.49-3.94-3.76-7.4-6.72-10.4c-0.65-0.66-1.08-1.39-0.98-2.33c0.11-1.06,0.67-1.84,1.68-2.22c1.07-0.4,2.04-0.16,2.85,0.64
                                            c2.11,2.11,3.93,4.44,5.46,7c1.96,3.27,3.35,6.78,4.2,10.49C86.58,37.96,86.6,38.23,86.62,38.37z"/>
                                    </g>
                                </svg>
                            </span>
                            <span>Notifications</span>
                        </span>
                        <span class="drop-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24.37" height="16.233" viewBox="0 0 24.37 16.233">
                                <path id="Line_Arrow_Right" data-name="Line Arrow Right" d="M3.967,0,0,4.048,12.185,16.233,24.37,4.048,20.4,0,12.185,8.3Z"/>
                            </svg>
                        </span>
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
                        <span class="title">
                            <span>
                                <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve">
                                    <g>
                                        <path d="M46.62,88.22c-0.78-0.29-1.6-0.49-2.32-0.89c-2.36-1.33-3.56-3.39-3.62-6.11c-0.03-1.54-0.03-1.52-1.42-2.08
                                            c-0.91-0.37-1.83-0.73-2.7-1.18c-0.48-0.25-0.77-0.21-1.12,0.18c-1.16,1.29-2.52,2.27-4.29,2.57c-2.17,0.37-4.15-0.08-5.79-1.55
                                            c-1.45-1.3-2.83-2.68-4.13-4.12c-2.37-2.64-2.32-6.7,0.21-9.26c1.01-1.01,1.29-1.79,0.5-3.01c-0.47-0.73-0.71-1.62-0.98-2.46
                                            c-0.15-0.48-0.37-0.63-0.88-0.65c-1-0.03-2.05-0.02-2.99-0.32c-2.8-0.87-4.54-2.84-4.83-5.75c-0.22-2.24-0.21-4.55,0.05-6.78
                                            c0.39-3.34,3.28-5.72,6.63-5.81c1.74-0.05,1.73-0.05,2.37-1.65c0.34-0.85,0.67-1.7,1.1-2.5c0.27-0.5,0.17-0.78-0.21-1.12
                                            c-1.23-1.11-2.19-2.4-2.52-4.08c-0.46-2.33,0.06-4.42,1.67-6.17c1.2-1.3,2.46-2.56,3.76-3.77c2.77-2.56,6.86-2.57,9.54,0.1
                                            c0.94,0.93,1.65,1.25,2.82,0.49c0.77-0.49,1.71-0.76,2.6-1.04c0.51-0.16,0.64-0.4,0.61-0.9c-0.1-1.63,0.13-3.2,1.04-4.6
                                            c1.34-2.07,3.25-3.22,5.71-3.28c2.06-0.05,4.15-0.1,6.2,0.14c3.17,0.38,5.72,3.29,5.66,6.5c-0.03,1.53,0.41,2.25,1.86,2.58
                                            c0.82,0.19,1.6,0.63,2.36,1.03c0.45,0.24,0.72,0.2,1.06-0.18c1.16-1.3,2.51-2.28,4.28-2.59c2.16-0.38,4.15,0.06,5.79,1.52
                                            c1.47,1.31,2.87,2.71,4.18,4.18c2.39,2.68,2.2,6.72-0.34,9.4c-1.11,1.17-1.09,1.16-0.45,2.66c0.4,0.93,0.72,1.89,1.13,2.81
                                            c0.09,0.2,0.39,0.41,0.61,0.44c0.71,0.07,1.44-0.01,2.14,0.1c2.95,0.45,5.15,2.54,5.81,5.46c0.03,0.14,0.1,0.27,0.15,0.4
                                            c0,2.27,0,4.54,0,6.8c-0.05,0.16-0.12,0.31-0.16,0.47c-0.52,2.1-1.72,3.68-3.63,4.7c-1.31,0.7-2.72,0.83-4.16,0.76
                                            c-0.47-0.02-0.69,0.12-0.84,0.59c-0.29,0.89-0.66,1.75-1.01,2.61c-0.21,0.52-0.7,1.07-0.62,1.53c0.08,0.46,0.7,0.84,1.08,1.25
                                            c2.6,2.79,2.65,6.83,0.06,9.62c-1.19,1.28-2.43,2.52-3.71,3.71c-1.79,1.66-3.93,2.18-6.3,1.67c-1.49-0.32-2.74-1.12-3.73-2.26
                                            c-0.51-0.59-0.94-0.72-1.66-0.31c-0.85,0.48-1.79,0.8-2.71,1.15c-1.13,0.43-1.15,0.42-1.13,1.62c0.04,2.67-0.98,4.81-3.25,6.24
                                            c-0.81,0.51-1.79,0.75-2.69,1.12C51.16,88.22,48.89,88.22,46.62,88.22z M50.04,16.91c-0.76,0-1.53-0.01-2.29,0
                                            c-1.56,0.02-2.6,1.04-2.62,2.59c-0.02,1.04,0.01,2.07-0.01,3.11c-0.02,1.18-0.61,1.97-1.75,2.27c-2.32,0.63-4.51,1.54-6.6,2.73
                                            c-1.12,0.64-2.01,0.49-2.95-0.42c-0.71-0.69-1.39-1.4-2.1-2.09c-1.16-1.12-2.6-1.12-3.74,0c-1.07,1.05-2.14,2.12-3.19,3.19
                                            c-1.13,1.15-1.13,2.59-0.01,3.74c0.69,0.71,1.4,1.39,2.09,2.1c0.93,0.96,1.06,1.84,0.39,3.01c-1.16,2.04-2.05,4.2-2.68,6.46
                                            c-0.35,1.25-1.11,1.81-2.43,1.83c-1.01,0.01-2.02-0.01-3.03,0.01c-1.46,0.03-2.5,1.08-2.52,2.54c-0.02,1.55-0.02,3.11,0,4.66
                                            c0.02,1.54,1.07,2.57,2.62,2.59c1.01,0.01,2.02-0.01,3.03,0.01c1.22,0.02,2,0.6,2.32,1.78c0.63,2.29,1.53,4.47,2.7,6.53
                                            c0.66,1.16,0.52,2.04-0.42,3.01c-0.69,0.71-1.4,1.39-2.09,2.1c-1.09,1.13-1.09,2.57,0,3.69c1.07,1.09,2.15,2.18,3.24,3.24
                                            c1.12,1.09,2.56,1.09,3.69,0.01c0.69-0.66,1.36-1.36,2.04-2.03c1.02-1.01,1.89-1.15,3.12-0.45c2.02,1.15,4.15,2.03,6.39,2.65
                                            c1.3,0.36,1.85,1.11,1.86,2.48c0.01,0.99-0.01,1.97,0.01,2.96c0.03,1.5,1.07,2.54,2.58,2.56c1.53,0.02,3.06,0.01,4.58,0
                                            c1.56-0.01,2.6-1.05,2.62-2.59c0.02-1.01-0.01-2.02,0.01-3.03c0.02-1.26,0.59-2.03,1.82-2.36c2.27-0.63,4.42-1.51,6.46-2.68
                                            c1.2-0.68,2.06-0.55,3.06,0.43c0.7,0.69,1.38,1.41,2.1,2.09c1.1,1.05,2.55,1.06,3.64,0c1.12-1.08,2.21-2.18,3.29-3.29
                                            c1.06-1.09,1.06-2.53,0.01-3.64c-0.69-0.73-1.43-1.43-2.14-2.15c-0.94-0.96-1.06-1.83-0.39-3.01c1.15-2.02,2.03-4.15,2.66-6.39
                                            c0.38-1.35,1.11-1.89,2.53-1.9c0.96-0.01,1.92,0.01,2.88-0.01c1.54-0.02,2.58-1.07,2.59-2.62c0.01-1.5,0.01-3.01,0-4.51
                                            c-0.01-1.6-1.04-2.64-2.63-2.66c-1.01-0.01-2.02,0.01-3.03-0.01c-1.22-0.02-2-0.61-2.32-1.78c-0.63-2.29-1.53-4.46-2.7-6.53
                                            c-0.66-1.16-0.52-2.04,0.43-3c0.69-0.7,1.41-1.38,2.09-2.1c1.09-1.14,1.09-2.57,0-3.69c-1.07-1.09-2.15-2.18-3.24-3.24
                                            c-1.12-1.09-2.56-1.09-3.69,0c-0.69,0.67-1.36,1.36-2.04,2.04c-1.03,1.01-1.9,1.15-3.12,0.44c-2.02-1.16-4.15-2.03-6.39-2.65
                                            c-1.27-0.35-1.84-1.1-1.86-2.41c-0.01-1.01,0.01-2.02-0.01-3.03c-0.03-1.5-1.08-2.53-2.58-2.56C51.57,16.9,50.81,16.91,50.04,16.91
                                            z"/>
                                        <path d="M66.49,50.36c-0.01,9.1-7.36,16.45-16.46,16.46c-9.12,0.01-16.51-7.41-16.47-16.52c0.04-9.11,7.41-16.44,16.49-16.42
                                            C59.16,33.91,66.5,41.26,66.49,50.36z M62.05,50.38c0.02-6.65-5.34-12.04-12-12.06c-6.65-0.02-12.04,5.34-12.06,12
                                            c-0.02,6.65,5.34,12.04,12,12.06C56.64,62.4,62.03,57.03,62.05,50.38z"/>
                                    </g>
                                </svg>
                            </span>
                            <span>Settings</span>
                        </span>
                        <span class="drop-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24.37" height="16.233" viewBox="0 0 24.37 16.233">
                                <path id="Line_Arrow_Right" data-name="Line Arrow Right" d="M3.967,0,0,4.048,12.185,16.233,24.37,4.048,20.4,0,12.185,8.3Z"/>
                            </svg>
                        </span>
                    </a>
                     <ul>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('user.info')}}">
                                <span class="d-flex">
                                    <span>
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
                                    </span>
                                    <span>{{__('education.info')}}</span>
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('user.certificate')}}">
                                <span class="d-flex">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 36">
                                            <path id="Path_132" data-name="Path 132"
                                                  d="M34.5,36h-12A1.5,1.5,0,0,1,21,34.5v-12A1.5,1.5,0,0,1,22.5,21h12A1.5,1.5,0,0,1,36,22.5v12A1.5,1.5,0,0,1,34.5,36Zm-12-13.5v12h12l0-12Zm12-7.5h-12A1.5,1.5,0,0,1,21,13.5V1.5A1.5,1.5,0,0,1,22.5,0h12A1.5,1.5,0,0,1,36,1.5v12A1.5,1.5,0,0,1,34.5,15ZM22.5,1.5v12h12l0-12ZM13.5,36H1.5A1.5,1.5,0,0,1,0,34.5v-12A1.5,1.5,0,0,1,1.5,21h12A1.5,1.5,0,0,1,15,22.5v12A1.5,1.5,0,0,1,13.5,36ZM1.5,22.5v12h12l0-12Zm12-7.5H1.5A1.5,1.5,0,0,1,0,13.5V1.5A1.5,1.5,0,0,1,1.5,0h12A1.5,1.5,0,0,1,15,1.5v12A1.5,1.5,0,0,1,13.5,15ZM1.5,1.5v12h12l0-12Z" />
                                        </svg>
                                    </span>
                                    <span>{{__('education.Certificates')}}</span>
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('user.change_password')}}">
                                <span class="d-flex">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 100 100" xml:space="preserve">
                                            <g>
                                                <path d="M51.34,6.77c1.51,0.22,3.02,0.41,4.52,0.66c6.68,1.13,12.72,3.76,18.18,7.75c0.16,0.11,0.32,0.22,0.65,0.45   c0-1.17-0.02-2.19,0.01-3.22c0.01-0.51,0.03-1.03,0.16-1.51c0.32-1.14,1.38-1.84,2.59-1.77c1.14,0.06,2.09,0.86,2.33,2.01   c0.07,0.36,0.1,0.73,0.1,1.1c0.01,2.81,0.01,5.62,0,8.42c0,2.16-0.69,2.95-2.8,3.32c-2.98,0.52-5.96,1.06-8.94,1.59   c-1.41,0.25-2.73-0.6-3.02-1.96c-0.3-1.39,0.49-2.73,1.9-3.04c1.24-0.28,2.5-0.46,3.75-0.69c0.19-0.03,0.37-0.1,0.84-0.24   c-1.82-1.13-3.39-2.23-5.07-3.13c-6.49-3.49-13.43-5.15-20.78-4.47C29.64,13.52,18.5,21.9,12.34,36.81   c-2.12,5.12-2.87,10.54-2.38,16.07c1.27,14.59,8.51,25.25,21.45,31.97c6.05,3.14,12.59,4.27,19.42,3.84   c15.64-0.97,29.36-11.7,34.13-26.82c1.18-3.76,1.72-7.61,1.8-11.54c0.03-1.33,0.95-2.38,2.18-2.53c1.29-0.16,2.44,0.57,2.81,1.82   c0.09,0.29,0.11,0.61,0.1,0.92c-0.07,10.32-3.34,19.52-9.9,27.48c-6.66,8.08-15.14,13.32-25.46,15.12   c-15.65,2.73-29.15-1.67-40.17-13.19c-5.99-6.27-9.56-13.82-11.05-22.35c-0.23-1.33-0.38-2.68-0.57-4.01c0-2.16,0-4.31,0-6.47   c0.19-1.34,0.34-2.68,0.57-4.01c1.51-8.68,5.23-16.27,11.3-22.66c6.85-7.22,15.2-11.65,25.05-13.17c1.14-0.18,2.28-0.33,3.42-0.5   C47.14,6.77,49.24,6.77,51.34,6.77z"/>
                                                <path d="M48.23,71.62c-4.99,0-9.98,0-14.97,0c-1.84,0-2.85-0.99-2.85-2.81c0-6.92,0-13.84,0-20.76c0-1.79,1-2.81,2.77-2.81   c10.07,0,20.14,0,30.2,0c1.76,0,2.77,1.02,2.77,2.81c0,6.92,0,13.84,0,20.76c0,1.82-1.01,2.81-2.85,2.81   C58.27,71.62,53.25,71.62,48.23,71.62z M48.24,59.7c0.59,0,1.19,0.03,1.78-0.01c1.39-0.09,2.48-1.19,2.5-2.51   c0.03-1.3-1.05-2.49-2.44-2.57c-1.21-0.07-2.43-0.07-3.64,0c-1.38,0.08-2.46,1.28-2.42,2.58c0.04,1.32,1.12,2.41,2.52,2.49   C47.11,59.73,47.68,59.7,48.24,59.7z"/>
                                                <path d="M39.77,40.09c-1.72,0-3.35,0-4.99,0c-1.02-7.85,1.65-15.33,10.7-17.53c7.43-1.81,15.26,3.61,16.21,11.18   c0.26,2.06,0.04,4.18,0.04,6.34c-1.52,0-3.18,0-4.94,0c0-1.34,0.02-2.69,0-4.04c-0.06-3.97-2.52-7.27-6.19-8.31   c-5.38-1.53-10.66,2.42-10.82,8.11C39.73,37.22,39.77,38.64,39.77,40.09z"/>
                                            </g>
                                        </svg>
                                    </span>
                                    <span>{{__('education.Change Password')}}</span>
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('user.logout')}}">
                                <span class="d-flex">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve">
                                            <style type="text/css">
                                                .st0{fill:#FFFFFF;}
                                            </style>
                                            <g>
                                                <path d="M65.16,8c0.19,0.1,0.38,0.2,0.57,0.28c1.83,0.78,2.8,2.64,2.39,4.6c-0.37,1.78-2,3.07-3.91,3.07c-12.38,0-24.75,0-37.13,0   c-2.67,0-4.71,1.42-5.4,3.79c-0.17,0.6-0.26,1.25-0.26,1.87c-0.01,19.2-0.01,38.39-0.01,57.59c0,3.44,2.27,5.69,5.73,5.69   c12.35,0,24.7,0,37.04,0c1.62,0,2.83,0.72,3.56,2.13c0.73,1.41,0.64,2.84-0.28,4.15c-0.76,1.08-1.82,1.67-3.16,1.67   c-12.73,0-25.47,0.03-38.2-0.01c-6.02-0.02-11.72-5.16-12.48-11.13c-0.11-0.85-0.17-1.7-0.17-2.56   c-0.01-19.17,0.05-38.34-0.04-57.5c-0.03-7.07,5.14-12.68,11.34-13.51c0.1-0.01,0.19-0.09,0.29-0.14C38.42,8,51.79,8,65.16,8z"/>
                                                <path d="M73.69,46.45c-0.31-0.34-0.51-0.56-0.71-0.76c-3.75-3.75-7.51-7.49-11.24-11.25c-2.26-2.28-1.4-5.9,1.59-6.76   c1.46-0.42,2.79-0.1,3.9,0.97c0.84,0.8,1.65,1.63,2.47,2.45c5.37,5.37,10.74,10.74,16.11,16.11c2.11,2.11,2.12,4.33,0.02,6.42   c-6.09,6.09-12.18,12.19-18.28,18.27c-1.91,1.9-4.63,1.88-6.18-0.04c-1.34-1.65-1.17-3.91,0.44-5.53   c3.53-3.54,7.06-7.07,10.61-10.6c0.39-0.39,0.82-0.73,1.24-1.09c-0.04-0.08-0.07-0.16-0.11-0.24c-0.28,0-0.57,0-0.85,0   c-10.05,0-20.11,0-30.16,0c-2.08,0-3.8-1.46-4.04-3.41c-0.26-2.06,0.99-3.91,3.02-4.43c0.39-0.1,0.82-0.1,1.23-0.11   c9.97-0.01,19.94,0,29.91,0C72.94,46.45,73.23,46.45,73.69,46.45z"/>
                                            </g>
                                        </svg>
                                    </span>
                                    <span>{{__('education.Logout')}}</span>
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</nav>


