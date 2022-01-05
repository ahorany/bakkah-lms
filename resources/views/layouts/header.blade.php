<style>
    a.messages {
        /* background: #6a6a6a; */
        display: inline-block;
        color: #fff;
        /* width: 35px; */
        text-align: center;
        height: 35px;
        line-height: 2.2;
        /* border-radius: 50%; */
    }
</style>
<header class="navbar navbar-dark sticky-top bg-white flex-md-nowrap p-0 shadow lms-header">
    <a class="navbar-brand col-md-3 col-lg-3 col-xl-2 me-0 px-3" href="{{CustomRoute('user.home')}}">
        <img src="{{CustomAsset('assets/images/logo.png')}}" alt="{{__('education.header_title')}}">
        <span class="d-none d-sm-block">BAKKAH <b>LMS</b></span>
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

    {{-- <span class="ml-auto">
        <h5 class="mb-0" style="font-weight: 700;">{{auth()->user()->trans_name}}</h5>
        <small>{{\auth()->user()->roles()->first()->trans_name}}</small>
    </span> --}}
    <ul class="navbar-nav mx-0">
        <li class="has-dropdown user messages">
            <ul class="navbar-nav mx-0">
                <li class="has-dropdown user m-0">
                    <a style="color: #6a6a6a;" class="messages" onclick="event.stopPropagation();this.nextElementSibling.classList.toggle('d-none'); return false;" href="#">
                        <span class="d-flex" style="width: 35px;
                        height: 35px;
                        object-fit: cover;
                        border-radius: 50%;
                        padding: 5px;
                        background: #F7F7F7;
                        /* box-shadow: 1px 1px 5px #eaeaea; */
                        ">
                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="25px"
                            viewBox="0 0 100 100" style="vertical-align: middle; fill: #5D5B5A; font-size:18px; width:23px;" xml:space="preserve">
                                <g>
                                    <path d="M45.91,12.57c0-1.23-0.03-2.59,0.01-3.95c0.05-2.18,1.22-3.64,3.22-4.1c2.12-0.48,4.39,1.03,4.68,3.18
                                        c0.12,0.92,0.08,1.86,0.1,2.79c0.01,0.71,0,1.42,0,2.06c1.9,0.53,3.77,0.88,5.5,1.57c10.3,4.07,16.38,11.58,18.09,22.55
                                        c0.23,1.47,0.27,2.97,0.27,4.46c0.03,6.77,0.02,13.55,0,20.32c0,0.75,0.22,1.27,0.76,1.79c2.03,1.97,4.05,3.95,6,6
                                        c2.14,2.25,1.31,5.71-1.55,6.62c-0.58,0.18-1.22,0.23-1.83,0.23c-20.82,0.01-41.65,0.01-62.47,0.01c-2.9,0-4.7-1.7-4.57-4.31
                                        c0.06-1.12,0.61-1.99,1.38-2.76c1.98-1.97,3.98-3.93,5.92-5.95c0.34-0.35,0.6-0.95,0.6-1.44c0.04-6.99,0-13.98,0.03-20.98
                                        c0.05-12.25,7.24-22.57,18.55-26.6C42.27,13.47,44.04,13.09,45.91,12.57z M71.94,68.04c-0.16-0.2-0.23-0.34-0.34-0.43
                                        c-1.47-1.18-1.85-2.73-1.84-4.55c0.05-7.33,0.03-14.67,0.01-22.01c0-1.27-0.07-2.55-0.25-3.81c-1.8-12.52-15.13-20.16-26.91-15.47
                                        c-7.1,2.83-12.67,9.85-12.59,18.95c0.07,7.52-0.01,15.04,0.03,22.56c0.01,1.69-0.41,3.12-1.74,4.22c-0.14,0.12-0.23,0.3-0.4,0.52
                                        C42.65,68.04,57.24,68.04,71.94,68.04z"/>
                                    <path d="M41.97,84.09c5.35,0,10.62,0,15.88,0c0.19,4.16-3.55,7.91-7.88,7.94C45.63,92.07,41.9,88.4,41.97,84.09z" />
                                </g>
                            </svg>
                           {{-- <span> {{__('education.Messages')}}</span> --}}
                        </span>
                    </a>

                    <div class="dropdown d-none">
                        <ul class="postition-relative">
                            <li>
                                <a href="{{route('user.add_message')}}">
                                    <span class="d-flex">
                                        <svg  version="1.1" id="svg_icon1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"  viewBox="0 0 60 60"  xml:space="preserve" style="margin-right: 4px;" >
                                        <path d="M57.28,30.77c-0.25,0.89-0.57,1.75-1.22,2.44c-0.94,1-2.09,1.53-3.47,1.53c-5.55,0-11.1,0-16.65,0c-0.21,0-0.41,0-0.69,0
                                        c0,0.23,0,0.43,0,0.64c0,5.57,0,11.13,0,16.7c0,2.37-1.73,4.34-4.08,4.67c-2.26,0.32-4.47-1.12-5.12-3.36
                                        c-0.13-0.46-0.18-0.96-0.19-1.44c-0.01-5.53-0.01-11.06-0.01-16.6c0-0.19,0-0.37,0-0.61c-0.25,0-0.46,0-0.66,0
                                        c-5.57,0-11.14,0-16.7,0c-2.16,0-4.08-1.52-4.55-3.6c-0.49-2.15,0.5-4.36,2.48-5.28c0.66-0.3,1.43-0.49,2.15-0.5
                                        c5.55-0.04,11.1-0.02,16.65-0.02c0.19,0,0.38,0,0.64,0c0-0.23,0-0.44,0-0.64c0-5.5,0-10.99,0-16.49c0-2.48,1.36-4.21,3.78-4.81
                                        c0.07-0.02,0.13-0.06,0.19-0.09c0.49,0,0.97,0,1.46,0c0.05,0.03,0.09,0.06,0.14,0.07c2.49,0.62,3.83,2.33,3.83,4.88
                                        c0,5.48,0,10.96,0,16.44c0,0.2,0,0.4,0,0.64c0.28,0,0.48,0,0.69,0c5.55,0,11.1,0,16.65,0c1.38,0,2.53,0.53,3.47,1.53
                                        c0.65,0.69,0.97,1.55,1.22,2.44C57.28,29.79,57.28,30.28,57.28,30.77z"/>
                                        </svg>
                                        {{__('education.Send Message')}}
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="{{route('user.messages.inbox',['type'=>'sent'])}}">
                                    <span class="d-flex">
                                        <svg version="1.1" id="Layer_2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" style="margin-right: 4px;" xml:space="preserve">
                                            <path d="M4.4,46.85c0.51-1.03,1.39-1.53,2.43-1.93C33.1,34.91,59.35,24.89,85.6,14.86c1.09-0.42,2.1-0.46,3,0.39
                                                c0.9,0.85,0.91,1.87,0.57,2.98C82.5,40.08,75.83,61.94,69.15,83.8c-0.68,2.24-2.46,2.82-4.35,1.42c-7.15-5.3-14.3-10.61-21.44-15.92
                                                c-0.26-0.2-0.53-0.38-0.86-0.61c-0.26,0.51-0.51,0.96-0.74,1.42c-2.8,5.55-5.59,11.09-8.38,16.64c-0.67,1.33-1.66,1.9-2.83,1.66
                                                c-1.32-0.26-2.07-1.19-2.07-2.6c0-8.13,0-16.27,0-24.4c0-0.69-0.03-1.39,0.01-2.07c0.04-0.53-0.15-0.78-0.65-0.97
                                                c-5.95-2.28-11.89-4.58-17.84-6.87c-1.18-0.46-2.35-0.95-3.56-1.36c-0.95-0.32-1.58-0.95-2.04-1.79C4.4,47.85,4.4,47.35,4.4,46.85z
                                                M81.28,26.86c-0.05-0.03-0.09-0.05-0.14-0.08C69.22,39.36,57.31,51.93,45.36,64.53c6.66,4.94,13.25,9.83,19.92,14.78
                                                C70.64,61.77,75.96,44.32,81.28,26.86z M13.96,47.59c0.3,0.13,0.49,0.23,0.7,0.31c5.11,1.98,10.24,3.94,15.34,5.95
                                                c0.54,0.21,0.87,0.04,1.27-0.24c11.5-8.19,23-16.38,34.5-24.58c1.01-0.72,2.01-1.44,3.01-2.16c-0.03-0.06-0.06-0.11-0.09-0.17
                                                C50.49,33.65,32.28,40.6,13.96,47.59z M33.45,75.04c0.05,0.02,0.1,0.03,0.14,0.05c0.13-0.23,0.26-0.46,0.38-0.7
                                                c1.7-3.37,3.4-6.76,5.12-10.12c0.29-0.56,0.66-1.09,1.09-1.55c8.63-9.13,17.27-18.24,25.91-27.35c0.2-0.21,0.4-0.44,0.6-0.65
                                                C55.66,42.4,44.74,50.17,33.83,57.96c-0.2,0.14-0.37,0.48-0.37,0.73C33.44,64.14,33.45,69.59,33.45,75.04z"/>
                                            </svg>
                                        {{__('education.Sent Items')}}
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="{{route('user.messages.inbox',['type'=>'inbox'])}}">
                                    <span class="d-flex">
                                        <svg version="1.1" id="Layer_3" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" style="margin-right: 4px;"xml:space="preserve">
                                            <g>
                                                <path d="M93.14,87.2c-28.75,0-57.39,0-86.13,0c0-0.39,0-0.74,0-1.09c0-9-0.05-18.01,0.04-27.01c0.02-2.34,0.47-4.69,0.77-7.02
                                                    c0.55-4.26,1.14-8.52,1.71-12.78c0.64-4.76,1.28-9.53,1.91-14.29c0.5-3.79,1-7.58,1.5-11.36c0.04-0.32,0.1-0.65,0.16-1.02
                                                    c24.63,0,49.24,0,73.97,0c0.17,1.16,0.35,2.35,0.51,3.55c0.77,5.77,1.54,11.54,2.3,17.31c0.74,5.62,1.47,11.25,2.21,16.87
                                                    c0.32,2.46,0.67,4.91,1,7.36c0.04,0.3,0.07,0.6,0.07,0.89c0,9.33,0,18.67,0,28C93.17,86.79,93.15,86.96,93.14,87.2z M82.02,18.37
                                                    c-21.35,0-42.6,0-63.87,0c-1.66,12.43-3.31,24.81-4.97,37.28c4.36,0,8.57,0,12.79,0c4.2,0,8.4,0,12.47,0
                                                    c0.18,1.97,0.17,3.82,0.55,5.58c1.11,5.23,6.29,9.07,11.6,8.76c5.6-0.33,10.22-4.53,10.82-9.92c0.16-1.44,0.17-2.9,0.25-4.46
                                                    c8.42,0,16.83,0,25.33,0C85.33,43.14,83.68,30.78,82.02,18.37z M12.79,81.45c24.9,0,49.74,0,74.57,0c0-6.71,0-13.36,0-20
                                                    c-0.15-0.02-0.24-0.04-0.33-0.04c-6.43,0-12.86,0-19.29-0.02c-0.61,0-0.71,0.27-0.82,0.78c-1.6,7.49-8.12,13.08-15.75,13.56
                                                    c-8,0.5-15.34-4.62-17.62-12.31c-0.19-0.65-0.36-1.32-0.53-1.97c-6.76,0-13.47,0-20.23,0C12.79,68.12,12.79,74.75,12.79,81.45z"/>
                                            </g>
                                        </svg>
                                        {{__('education.Inbox')}}
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </li>
        <li class="has-dropdown user">
            <a onclick="event.stopPropagation();this.nextElementSibling.classList.toggle('d-none'); return false;" class="nav-link" href="#">
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
                <img style="width:35px;height:35px;object-fit:cover;border-radius: 50%;" src="{{$url}}" alt=" " />

                <svg xmlns="http://www.w3.org/2000/svg" style="margin-right: -15px;" width="10.125" height="6.382" viewBox="0 0 10.125 6.382">
                    <path id="Path_114" data-name="Path 114" d="M6.382,5.063,0,0V10.125Z"
                          transform="translate(10.125) rotate(90)" fill="#363636" />
                </svg>
            </a>

            <div class="dropdown d-none">
                <ul class="postition-relative">
                    <li class="dropdown-item borderBottom p-20" style="border-bottom: 1px solid gainsboro;">
                        <div>
                            <h2 style="font-size: 1.1rem; margin: 0; ">{{auth()->user()->trans_name}}</h2>
                            {{-- <small style="color: #73726c; font-weight:700;">{{$user_role_name}}</small> --}}
                        </div>
                    </li>
                    <li>
                        <a href="{{route('user.info')}}" class="d-flex">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve">
                                <style type="text/css">
                                    .st0{fill:#FFFFFF;}
                                </style>
                                <g>
                                    <path d="M96.47,46.82c0,2.17,0,4.35,0,6.52c-0.2,1.49-0.36,2.98-0.62,4.46c-1.36,7.91-4.49,15.04-9.55,21.28   c-7.27,8.96-16.59,14.55-27.96,16.66c-1.65,0.31-3.33,0.49-4.99,0.73c-2.17,0-4.35,0-6.52,0c-0.26-0.06-0.52-0.13-0.79-0.17   c-2.32-0.37-4.67-0.59-6.94-1.14C15.97,89.57,0.75,67.14,4.11,43.64c1.19-8.32,4.27-15.89,9.64-22.38   C25.24,7.39,40.03,1.77,57.81,4.33c8,1.15,15.15,4.51,21.42,9.64c8.9,7.29,14.46,16.6,16.52,27.95   C96.04,43.54,96.23,45.18,96.47,46.82z M10.22,50.03c-0.02,22.01,17.83,39.9,39.82,39.93c22,0.03,39.9-17.83,39.92-39.83   c0.02-22.01-17.82-39.9-39.82-39.93C28.13,10.18,10.24,28.03,10.22,50.03z"/>
                                    <path class="st0" d="M96.47,46.82c-0.24-1.63-0.42-3.27-0.72-4.9c-2.06-11.35-7.62-20.66-16.52-27.95   c-6.26-5.13-13.42-8.49-21.42-9.64C40.03,1.77,25.24,7.39,13.75,21.26C8.38,27.75,5.3,35.32,4.11,43.64   C0.75,67.14,15.97,89.57,39.1,95.16c2.27,0.55,4.63,0.76,6.94,1.14c0.26,0.04,0.52,0.12,0.79,0.17c-14.43,0-28.86,0-43.29,0   c0-30.97,0-61.94,0-92.94c30.97,0,61.96,0,92.94,0C96.47,17.95,96.47,32.39,96.47,46.82z"/>
                                    <path class="st0" d="M53.35,96.47c1.67-0.24,3.34-0.43,4.99-0.73c11.37-2.1,20.69-7.69,27.96-16.66   c5.06-6.24,8.19-13.37,9.55-21.28c0.25-1.48,0.41-2.97,0.62-4.46c0,14.38,0,28.75,0,43.13C82.09,96.47,67.72,96.47,53.35,96.47z"/>
                                    <path d="M45.38,56.78c0-3.59,0-7.19,0-10.78c0-1.98,0.63-2.83,2.55-3.37c1.77-0.5,3.52-0.42,5.21,0.35   c1.14,0.52,1.74,1.37,1.74,2.7c-0.03,7.46-0.02,14.92,0,22.38c0,1.04-0.39,1.82-1.25,2.35c-2.21,1.37-4.49,1.43-6.78,0.2   c-0.99-0.54-1.5-1.37-1.49-2.58C45.41,64.27,45.38,60.53,45.38,56.78z"/>
                                    <path d="M55.19,32.38c-0.23,2.52-2.7,4.57-5.35,4.38c-1.58-0.11-2.91-0.71-3.91-1.97c-1.12-1.42-1.17-3.21-0.14-4.69   c1.98-2.85,6.74-2.8,8.58,0.14C54.78,30.87,54.93,31.66,55.19,32.38z"/>
                                </g>
                            </svg>
                            <span class="mx-1">
                                {{__('education.info')}}
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('user.certificate')}}" class="d-flex">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 36">
                                <path id="Path_132" data-name="Path 132"
                                      d="M34.5,36h-12A1.5,1.5,0,0,1,21,34.5v-12A1.5,1.5,0,0,1,22.5,21h12A1.5,1.5,0,0,1,36,22.5v12A1.5,1.5,0,0,1,34.5,36Zm-12-13.5v12h12l0-12Zm12-7.5h-12A1.5,1.5,0,0,1,21,13.5V1.5A1.5,1.5,0,0,1,22.5,0h12A1.5,1.5,0,0,1,36,1.5v12A1.5,1.5,0,0,1,34.5,15ZM22.5,1.5v12h12l0-12ZM13.5,36H1.5A1.5,1.5,0,0,1,0,34.5v-12A1.5,1.5,0,0,1,1.5,21h12A1.5,1.5,0,0,1,15,22.5v12A1.5,1.5,0,0,1,13.5,36ZM1.5,22.5v12h12l0-12Zm12-7.5H1.5A1.5,1.5,0,0,1,0,13.5V1.5A1.5,1.5,0,0,1,1.5,0h12A1.5,1.5,0,0,1,15,1.5v12A1.5,1.5,0,0,1,13.5,15ZM1.5,1.5v12h12l0-12Z" />
                            </svg>
                            <span class="mx-1">
                                {{__('education.Certificates')}}
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('user.change_password')}}" class="d-flex">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 100 100" xml:space="preserve">
                                <g>
                                    <path d="M51.34,6.77c1.51,0.22,3.02,0.41,4.52,0.66c6.68,1.13,12.72,3.76,18.18,7.75c0.16,0.11,0.32,0.22,0.65,0.45   c0-1.17-0.02-2.19,0.01-3.22c0.01-0.51,0.03-1.03,0.16-1.51c0.32-1.14,1.38-1.84,2.59-1.77c1.14,0.06,2.09,0.86,2.33,2.01   c0.07,0.36,0.1,0.73,0.1,1.1c0.01,2.81,0.01,5.62,0,8.42c0,2.16-0.69,2.95-2.8,3.32c-2.98,0.52-5.96,1.06-8.94,1.59   c-1.41,0.25-2.73-0.6-3.02-1.96c-0.3-1.39,0.49-2.73,1.9-3.04c1.24-0.28,2.5-0.46,3.75-0.69c0.19-0.03,0.37-0.1,0.84-0.24   c-1.82-1.13-3.39-2.23-5.07-3.13c-6.49-3.49-13.43-5.15-20.78-4.47C29.64,13.52,18.5,21.9,12.34,36.81   c-2.12,5.12-2.87,10.54-2.38,16.07c1.27,14.59,8.51,25.25,21.45,31.97c6.05,3.14,12.59,4.27,19.42,3.84   c15.64-0.97,29.36-11.7,34.13-26.82c1.18-3.76,1.72-7.61,1.8-11.54c0.03-1.33,0.95-2.38,2.18-2.53c1.29-0.16,2.44,0.57,2.81,1.82   c0.09,0.29,0.11,0.61,0.1,0.92c-0.07,10.32-3.34,19.52-9.9,27.48c-6.66,8.08-15.14,13.32-25.46,15.12   c-15.65,2.73-29.15-1.67-40.17-13.19c-5.99-6.27-9.56-13.82-11.05-22.35c-0.23-1.33-0.38-2.68-0.57-4.01c0-2.16,0-4.31,0-6.47   c0.19-1.34,0.34-2.68,0.57-4.01c1.51-8.68,5.23-16.27,11.3-22.66c6.85-7.22,15.2-11.65,25.05-13.17c1.14-0.18,2.28-0.33,3.42-0.5   C47.14,6.77,49.24,6.77,51.34,6.77z"/>
                                    <path class="st0" d="M45.04,6.77c-1.14,0.17-2.28,0.32-3.42,0.5c-9.86,1.52-18.2,5.96-25.05,13.17c-6.07,6.39-9.8,13.98-11.3,22.66   c-0.23,1.33-0.38,2.67-0.57,4.01c0-13.43,0-26.87,0-40.34C18.15,6.77,31.6,6.77,45.04,6.77z"/>
                                    <path d="M48.23,71.62c-4.99,0-9.98,0-14.97,0c-1.84,0-2.85-0.99-2.85-2.81c0-6.92,0-13.84,0-20.76c0-1.79,1-2.81,2.77-2.81   c10.07,0,20.14,0,30.2,0c1.76,0,2.77,1.02,2.77,2.81c0,6.92,0,13.84,0,20.76c0,1.82-1.01,2.81-2.85,2.81   C58.27,71.62,53.25,71.62,48.23,71.62z M48.24,59.7c0.59,0,1.19,0.03,1.78-0.01c1.39-0.09,2.48-1.19,2.5-2.51   c0.03-1.3-1.05-2.49-2.44-2.57c-1.21-0.07-2.43-0.07-3.64,0c-1.38,0.08-2.46,1.28-2.42,2.58c0.04,1.32,1.12,2.41,2.52,2.49   C47.11,59.73,47.68,59.7,48.24,59.7z"/>
                                    <path d="M39.77,40.09c-1.72,0-3.35,0-4.99,0c-1.02-7.85,1.65-15.33,10.7-17.53c7.43-1.81,15.26,3.61,16.21,11.18   c0.26,2.06,0.04,4.18,0.04,6.34c-1.52,0-3.18,0-4.94,0c0-1.34,0.02-2.69,0-4.04c-0.06-3.97-2.52-7.27-6.19-8.31   c-5.38-1.53-10.66,2.42-10.82,8.11C39.73,37.22,39.77,38.64,39.77,40.09z"/>
                                </g>
                            </svg>
                            <span class="mx-1">
                                {{__('education.Change Password')}}
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('user.logout')}}" class="d-flex">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve">
                                <style type="text/css">
                                    .st0{fill:#FFFFFF;}
                                </style>
                                <g>
                                    <path d="M65.16,8c0.19,0.1,0.38,0.2,0.57,0.28c1.83,0.78,2.8,2.64,2.39,4.6c-0.37,1.78-2,3.07-3.91,3.07c-12.38,0-24.75,0-37.13,0   c-2.67,0-4.71,1.42-5.4,3.79c-0.17,0.6-0.26,1.25-0.26,1.87c-0.01,19.2-0.01,38.39-0.01,57.59c0,3.44,2.27,5.69,5.73,5.69   c12.35,0,24.7,0,37.04,0c1.62,0,2.83,0.72,3.56,2.13c0.73,1.41,0.64,2.84-0.28,4.15c-0.76,1.08-1.82,1.67-3.16,1.67   c-12.73,0-25.47,0.03-38.2-0.01c-6.02-0.02-11.72-5.16-12.48-11.13c-0.11-0.85-0.17-1.7-0.17-2.56   c-0.01-19.17,0.05-38.34-0.04-57.5c-0.03-7.07,5.14-12.68,11.34-13.51c0.1-0.01,0.19-0.09,0.29-0.14C38.42,8,51.79,8,65.16,8z"/>
                                    <path d="M73.69,46.45c-0.31-0.34-0.51-0.56-0.71-0.76c-3.75-3.75-7.51-7.49-11.24-11.25c-2.26-2.28-1.4-5.9,1.59-6.76   c1.46-0.42,2.79-0.1,3.9,0.97c0.84,0.8,1.65,1.63,2.47,2.45c5.37,5.37,10.74,10.74,16.11,16.11c2.11,2.11,2.12,4.33,0.02,6.42   c-6.09,6.09-12.18,12.19-18.28,18.27c-1.91,1.9-4.63,1.88-6.18-0.04c-1.34-1.65-1.17-3.91,0.44-5.53   c3.53-3.54,7.06-7.07,10.61-10.6c0.39-0.39,0.82-0.73,1.24-1.09c-0.04-0.08-0.07-0.16-0.11-0.24c-0.28,0-0.57,0-0.85,0   c-10.05,0-20.11,0-30.16,0c-2.08,0-3.8-1.46-4.04-3.41c-0.26-2.06,0.99-3.91,3.02-4.43c0.39-0.1,0.82-0.1,1.23-0.11   c9.97-0.01,19.94,0,29.91,0C72.94,46.45,73.23,46.45,73.69,46.45z"/>
                                </g>
                            </svg>
                            <span class="mx-1">
                                {{__('education.Logout')}}
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>
    </ul>
</header>


