@extends('layouts.app')

@section('useHead')
    <title>{{__('education.home')}} | {{ __('home.DC_title') }}</title>
@endsection

@section('content')
    <div class="user-info">
        <div class="row home-section hero">
            <div class="col-md-12 col-xl-12">
                <div class="card p-30">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <h2 class="mt-0">Hi, {{auth()->user()->trans_name}}</h2>
                            <p class="lead" style="text-transform: none !important;">
                                Welcome to Bakkah Learning Management System!
                                <br>
                                We are so happy to have you here and can't wait to start our journey together towards success and glory. Through our interactive self-paced system, you can easily access all the information you need in an endeavor to improve a more comfortable and enjoyable learning experience for students of all backgrounds and abilities.
                                <br>
                                A lot of Knowledge & fun are waiting for you, so let's get started.
                            </p>
                        </div>

                        <div class="col-lg-4 mt-4 mt-lg-0 text-center">
                            <img src="{{CustomAsset('assets/images/dash.png')}}" {{(auth()->user()->bio == null) ? 'style='.' width:'.'60%;' : '' }} alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
            function getReportNumber($complete_courses,$status){
                if($complete_courses){
                    foreach ($complete_courses as $complete_course){
                        if($complete_course->status == $status){
                            return  str_pad($complete_course->courses_count, 2, '0', STR_PAD_LEFT);
                        }
                    }
                }
                return 0;
            }
        ?>
        <div class="row home-section reports">
            <div class="col-lg-12">
                <div class="card h-100 p-30 courses">

                    <div class="d-flex flex-column flex-sm-row flex-wrap justify-content-center">

                        <div class="course-cards" style="border-color: rgb(251 68 0 / 50%);">
                            {{-- <svg xmlns="http://www.w3.org/2000/svg" width="71.3" height="71.3" viewBox="0 0 71.3 71.3" class="icon-report bg-main">
                                <path id="Path_164" data-name="Path 164" d="M254.387,629.475h-8.913v8.912a8.91,8.91,0,0,1-8.912,8.913h-35.65A8.91,8.91,0,0,1,192,638.387v-35.65a8.91,8.91,0,0,1,8.913-8.912h8.913v-8.913A8.91,8.91,0,0,1,218.737,576h35.65a8.91,8.91,0,0,1,8.912,8.912v35.65a8.91,8.91,0,0,1-8.912,8.913Zm-53.475,8.912h35.65v-35.65h-35.65v35.65Zm53.475-53.475h-35.65v8.913h17.825a8.91,8.91,0,0,1,8.912,8.912v17.825h8.913v-35.65Z" transform="translate(-192 -576)" fill-rule="evenodd"></path>
                            </svg> --}}
                            <svg version="1.1" class="icon-report bg-main" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" xml:space="preserve">
                                <style type="text/css">
                                    .st0{fill:none;stroke:#fff;stroke-width:3.0215;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:22.9256;}
                                    .st1{fill:none;stroke:#fff;stroke-width:3.0221;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:22.9256;}
                                </style>
                                <g>
                                    <polyline class="st0" points="81.49,25.18 86.65,25.18 86.65,70.19 12.29,70.19 12.29,25.18 17.45,25.18 	"/>
                                    <path class="st1" d="M31.38,83.94h36.19 M43.28,82.59V72.01 M55.66,82.59V72.01 M26.35,24.55c9.22,0,10.82,0,15.53,0 M26.35,32.64
                                        c9.22,0,10.82,0,15.53,0 M26.35,40.74c9.22,0,10.82,0,15.53,0 M26.35,48.84c9.22,0,10.82,0,15.53,0 M18.96,16.33
                                        c6.84,0,19.8,0,26.63,0l3.69,4.02c0,13.87,0,27.04,0,40.91l-3.69-4.02c-6.84,0-19.8,0-26.63,0C18.96,43.37,18.96,30.2,18.96,16.33
                                        L18.96,16.33z M53.35,16.33l-3.69,4.02 M49.66,61.27l3.69-4.02 M53.35,57.25c6.84,0,19.79,0,26.63,0c0-13.87,0-27.04,0-40.91
                                        c-6.84,0-19.79,0-26.63,0"/>
                                    <polyline class="st0" points="79.99,57.77 79.99,63.65 18.95,63.65 18.95,57.64 	"/>
                                    <path class="st1" d="M57.05,24.55c9.22,0,10.82,0,15.53,0 M57.05,32.64c9.22,0,10.82,0,15.53,0 M57.05,40.74
                                        c9.22,0,10.82,0,15.53,0"/>
                                </g>
                            </svg>
                            <div>
                                <span>My Courses</span>
                                <b>{{((count($courses->courses) > 9) || (count($courses->courses) == 0) ? count($courses->courses) : '0'.count($courses->courses))}}</b>
                            </div>
                        </div>

                        <div class="course-cards" style="border-color: rgb(30 191 184 / 50%);">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" class="icon-report bg-third" id="bg-main" x="0px" y="0px" viewBox="0 0 60 60" style="enable-background:new 0 0 60 60;" xml:space="preserve">
                                <path d="M32.47,29.61c0.84,0.83,1.65,1.64,2.46,2.46c1.86,1.86,3.73,3.72,5.58,5.59c0.93,0.95,0.74,2.31-0.37,2.83  c-0.7,0.33-1.38,0.17-2.06-0.5c-2.48-2.48-4.96-4.95-7.43-7.43c-0.17-0.17-0.32-0.35-0.53-0.59c-0.21,0.2-0.38,0.35-0.54,0.51  c-2.53,2.52-5.05,5.05-7.57,7.57c-0.52,0.52-1.11,0.75-1.83,0.5c-0.66-0.23-1.03-0.71-1.13-1.39c-0.1-0.67,0.24-1.16,0.69-1.61  c2.47-2.46,4.93-4.92,7.39-7.38c0.17-0.17,0.38-0.3,0.62-0.49c-0.24-0.26-0.4-0.43-0.56-0.59c-2.29-2.29-4.59-4.58-6.88-6.88  c-1-1.01-0.72-2.45,0.54-2.9c0.75-0.27,1.35,0.02,1.88,0.56c2.26,2.26,4.53,4.52,6.79,6.79c0.17,0.17,0.29,0.38,0.46,0.61  c0.27-0.25,0.45-0.41,0.61-0.57c2.26-2.26,4.53-4.52,6.79-6.79c0.51-0.51,1.08-0.83,1.81-0.64c1.2,0.31,1.65,1.75,0.86,2.71  c-0.15,0.18-0.33,0.35-0.5,0.52c-2.22,2.22-4.43,4.43-6.65,6.65C32.77,29.28,32.64,29.43,32.47,29.61z"></path>
                                <path d="M31.74,55.25c-1.12,0-2.24,0-3.35,0c-0.16-0.03-0.32-0.07-0.48-0.1c-1.02-0.15-2.06-0.22-3.06-0.44  c-5.29-1.14-9.78-3.71-13.43-7.7c-3.29-3.6-5.38-7.8-6.21-12.61c-0.16-0.9-0.26-1.81-0.39-2.71c0-1.12,0-2.23,0-3.35  c0.03-0.14,0.07-0.28,0.09-0.42c0.11-0.86,0.17-1.73,0.34-2.58c1.24-6.24,4.33-11.37,9.37-15.25c5.96-4.6,12.73-6.22,20.12-4.86  c6.2,1.13,11.29,4.26,15.17,9.23c4.68,6,6.33,12.81,4.95,20.28c-1.14,6.18-4.29,11.22-9.21,15.13c-3.3,2.62-7.03,4.3-11.19,5.01  C33.55,55.01,32.64,55.12,31.74,55.25z M51.16,29.99C51.15,18.41,41.63,8.91,30.06,8.91C18.48,8.91,8.97,18.42,8.96,30  C8.95,41.59,18.5,51.14,30.09,51.11C41.68,51.08,51.18,41.56,51.16,29.99z"></path>
                            </svg>
                            <div>
                                <span>Courses Not Started</span>
                                <b>{{ getReportNumber($complete_courses,2)  }}</b>
                            </div>
                        </div>

                        <div class="course-cards" style="border-color: rgb(253 154 24 / 50%);">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" class="icon-report bg-two" id="bg-one" x="0px" y="0px" viewBox="0 0 60 60" style="enable-background:new 0 0 60 60;" xml:space="preserve">
                                <g>
                                    <path d="M30.56,8.45c0.71,0.3,1.31,0.72,1.49,1.53c0.03,0.14,0.06,0.28,0.06,0.42c0,2.72,0.01,5.45,0,8.17   c-0.01,1.03-0.77,1.82-1.83,1.96c-0.88,0.12-1.83-0.5-2.09-1.39c-0.08-0.28-0.12-0.6-0.12-0.89c-0.01-2.38,0.03-4.76-0.02-7.14   c-0.03-1.25,0.34-2.19,1.56-2.67C29.92,8.45,30.24,8.45,30.56,8.45z"/>
                                    <path d="M32.11,46.76c0,1.3,0,2.6,0,3.9c0,1.2-0.83,2.1-1.94,2.12c-1.18,0.03-2.1-0.82-2.11-2.04c-0.03-2.67-0.03-5.34,0-8.01   c0.01-1.19,0.97-2.1,2.07-2.05c1.14,0.04,1.97,0.94,1.97,2.15C32.12,44.14,32.11,45.45,32.11,46.76z"/>
                                    <path d="M13.83,16.37c0.01-0.84,0.4-1.47,1.15-1.83c0.76-0.36,1.49-0.24,2.15,0.27c0.1,0.08,0.19,0.17,0.29,0.26   c1.83,1.83,3.66,3.65,5.48,5.48c0.95,0.96,0.87,2.36-0.16,3.13c-0.71,0.53-1.65,0.53-2.38,0c-0.18-0.14-0.35-0.3-0.51-0.46   c-1.77-1.76-3.53-3.53-5.3-5.29C14.11,17.5,13.81,17.01,13.83,16.37z"/>
                                    <path d="M23.55,39.17c0.02,0.57-0.2,1.04-0.6,1.44c-1.88,1.89-3.76,3.79-5.66,5.66c-0.85,0.84-2.06,0.83-2.87,0.03   c-0.79-0.78-0.81-2.04,0.01-2.87c1.87-1.9,3.76-3.79,5.66-5.66c0.64-0.64,1.43-0.81,2.27-0.44C23.14,37.67,23.54,38.31,23.55,39.17   z"/>
                                    <path d="M36.62,39.07c0.04-0.72,0.4-1.35,1.14-1.71c0.74-0.36,1.45-0.27,2.12,0.21c0.09,0.07,0.18,0.15,0.26,0.23   c1.86,1.86,3.72,3.71,5.57,5.57c1.05,1.06,0.8,2.68-0.48,3.3c-0.71,0.35-1.4,0.27-2.05-0.17c-0.14-0.1-0.27-0.22-0.39-0.34   c-1.82-1.81-3.63-3.63-5.45-5.45C36.9,40.29,36.62,39.79,36.62,39.07z"/>
                                    <path d="M13.96,32.61c-1.4,0-2.8,0.04-4.2-0.01c-1.05-0.04-1.83-0.93-1.84-1.98c-0.01-1.04,0.78-1.92,1.82-2   c0.49-0.04,0.98-0.03,1.47-0.03c2.32,0.01,4.65-0.01,6.97,0.04c1.06,0.02,1.83,0.92,1.83,1.98c0.01,1.05-0.77,1.96-1.81,2   C16.79,32.65,15.37,32.61,13.96,32.61C13.96,32.61,13.96,32.61,13.96,32.61z"/>
                                    <path d="M46.19,32.61c-0.74,0-1.47,0.04-2.21-0.01c-1.01-0.06-1.8-0.95-1.82-1.97c-0.01-1.03,0.79-1.97,1.82-2   c1.48-0.05,2.97-0.05,4.45,0c1.02,0.03,1.79,0.93,1.81,1.96c0.02,1.04-0.78,1.92-1.81,2.02c-0.1,0.01-0.2,0.02-0.3,0.03   c-0.65,0-1.3,0-1.95,0C46.19,32.63,46.19,32.62,46.19,32.61z"/>
                                </g>
                            </svg>
                            <div>
                                <span>Courses In Progess</span>
                                <b>{{ getReportNumber($complete_courses,0)  }}</b>
                            </div>
                        </div>

                        <div class="course-cards last" style="border-color: rgb(173 173 173 / 50%);">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" class="icon-report bg-five" id="bg-two" x="0px" y="0px" viewBox="0 0 60 60" style="enable-background:new 0 0 60 60;" xml:space="preserve">
                                <g>
                                    <path d="M31.97,55.25c-1.12,0-2.24,0-3.35,0c-0.16-0.03-0.32-0.07-0.48-0.1c-1.02-0.15-2.06-0.22-3.06-0.44   c-5.29-1.14-9.78-3.71-13.43-7.7c-3.29-3.6-5.38-7.8-6.21-12.61c-0.16-0.9-0.26-1.81-0.39-2.71c0-1.12,0-2.23,0-3.35   c0.03-0.14,0.07-0.28,0.09-0.42c0.11-0.86,0.17-1.73,0.34-2.58c1.24-6.24,4.33-11.37,9.37-15.25c5.96-4.6,12.73-6.22,20.12-4.86   c6.2,1.13,11.29,4.26,15.17,9.23c4.68,6,6.33,12.81,4.95,20.28c-1.14,6.18-4.29,11.22-9.21,15.13c-3.3,2.62-7.03,4.3-11.19,5.01   C33.78,55.01,32.87,55.12,31.97,55.25z M51.39,29.99C51.38,18.41,41.86,8.91,30.29,8.91C18.71,8.91,9.2,18.42,9.19,30   c-0.01,11.59,9.55,21.14,21.13,21.11C41.91,51.08,51.41,41.56,51.39,29.99z"/>
                                    <path d="M26.32,35.36c0.13-0.19,0.22-0.37,0.36-0.51c4.77-4.78,9.55-9.55,14.32-14.33c0.55-0.55,1.16-0.9,1.97-0.78   c0.78,0.11,1.33,0.54,1.63,1.26c0.31,0.72,0.2,1.41-0.27,2.04c-0.14,0.18-0.3,0.35-0.46,0.51C38.57,28.84,33.28,34.12,28,39.4   c-1.23,1.23-2.31,1.23-3.53,0.02c-2.64-2.64-5.28-5.27-7.91-7.91c-1.08-1.09-0.93-2.69,0.32-3.39c0.78-0.44,1.54-0.38,2.26,0.14   c0.19,0.13,0.35,0.3,0.51,0.46c2.06,2.05,4.11,4.11,6.17,6.17C25.95,35.01,26.08,35.13,26.32,35.36z"/>
                                </g>
                            </svg>
                            <div>

                                <span>Courses Completed</span>
                                <b>{{ getReportNumber($complete_courses,1)  }}</b>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        @if (count($courses->courses) > 0)
            <div class="row home-section">
                <div class="col-xl-12 col-md-12">
                    <div class="card p-30 ">
                        <h3 class="mb-5">{{ __('education.Course Overview') }}</h3>
                        <div class="row">
                            @forelse($courses->courses as $course)
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 mb-4">
                                <a href="{{CustomRoute('user.course_details',$course->id)}}">
                                <div class="text-center course-image p-3 @if ($loop->last) last @endif">
                                    <?php
                                        $url = '';
                                        // dd($course);
                                        if($course->upload != null) {
                                            // if (file_exists($course->upload->file) == false){
                                            //     $url = 'https://ui-avatars.com/api/?background=fb4400&color=fff&name=' . $course->trans_title;
                                            // }else{
                                                $url = $course->upload->file;
                                                $url = CustomAsset('upload/thumb200/'. $url);
                                            // }
                                        }else {
                                            $url = 'https://ui-avatars.com/api/?background=6a6a6a&color=fff&name=' . $course->trans_title;
                                        }
                                    ?>
                                    @isset($course->upload->file)
                                        <div class="image" style="height: 120px; display:flex; align-items: center; justify-content: center;">
                                            <img src="{{$url}}" height="auto" width="80%">
                                        </div>
                                    @else
                                        <div class="image no-img" style="height: 120px; display:flex; align-items: center; justify-content: center;">
                                            <img src="{{$url}}" height="auto" width="100px">
                                        </div>
                                    @endisset
                                    @if($course->pivot->paid_status == 504)
                                        <span class="status">Free</span>
                                    @endif
                                    <h3 style="color: #000; margin: 0; margin-top: 5px; min-height: 50px; font-weight:normal; display: flex; justify-content: center; align-items: center;">
                                        {{$course->trans_title}}
                                    </h3>
                                    @php
                                        $type = [
                                            '11' => 'self-paced',
                                            '13' => 'live-online',
                                            '353' => 'exam-simulators',
                                            '383' => 'instructor-led',
                                        ];
                                    @endphp
                                    <span class="badge my-1 {{ $type[$course->deliveryMethod->id] }}">{{$course->deliveryMethod->trans_name}}</span>

                                    <div class="progress">
                                        <div style="width: {{$course->pivot->progress??0}}% !important;" class="bar"></div>
                                    </div>
                                    <small>{{$course->pivot->progress??0}}% Complete</small>
                                </div>
                                </a>
                            </div>
                            @empty
                            <p>Not found any course!!</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row home-section">
            <div class="col-lg-6 col-md-12 col-sm-12 col-12 m-bottom">
                <div class="card h-100 p-30">
                    <h3>Courses Progress Overview</h3>
                    <canvas class="w-100" id="oilChart" height="400"></canvas>
                </div>
            </div>

            <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                @include('Html.activity-card', [
                    'activities'=>$activities,
                    'cls'=>'card h-100 p-30 activity',
                ])
            </div>
        </div>

        @if($last_video)
            <div class="row home-section">
                <div class="col-lg-6 col-md-12 col-sm-12 col-12 mb-4">
                    <div class="card h-100 p-30">
                        <h3>{{ __('education.Last Video View') }}</h3>
                        @if($last_video->url == null)
                            <video preload="metadata" controls controlsList="nodownload">
                                <source  src="{{CustomAsset('upload/files/videos/'.$last_video->file)}}#t=0.2">
                            </video>
                        @else
                            <?php
                            if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $last_video->url, $match)) {
                                $video_id = $match[1]??null;
                            }
                            ?>
                            <iframe style="" width="100%" height="323px" allowfullscreen="" src='https://www.youtube.com/embed/{{$video_id??null}}' ></iframe>

                        @endif
                    </div>
                </div>
                @if(count($next_videos) >0 )
                    <div class="col-lg-6 col-md-12 col-sm-12 col-12 mb-4">
                        <div class="card h-100 p-30">
                            <h3>{{ __('education.Next Video') }}</h3>
                            <ul class="video-list">
                                @foreach($next_videos as $next_video)
                                    <li>
                                        {{-- <div class="play">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="17.325" height="19.732" viewBox="0 0 17.325 19.732">
                                                <path id="Path_92" data-name="Path 92" d="M1586.871,1153.329V1133.6l17.325,9.8Z" transform="translate(-1586.871 -1133.597)" fill="#fff"/>
                                            </svg>
                                        </div> --}}
                                        {{-- class="svg-icons" --}}
                                        <img style="width:30px; height:30px; opacity: 0.6;" src="{{CustomAsset('icons/video.svg')}}" alt="{{$next_video->title}}"/>
                                        <div class="text" style="margin: 1px 5px; padding-top: 1px;">
                                            <h5><a href="{{CustomRoute('user.course_preview',$next_video->id)}}">{{$next_video->title}}</a> </h5>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>
@endsection

@section('script')
    <script>
        var month = [
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December"
        ];
        var weekday = [
            "Sunday",
            "Monday",
            "Tuesday",
            "Wednesday",
            "Thursday",
            "Friday",
            "Saturday"
        ];
        var weekdayShort = [
            "sun",
            "mon",
            "tue",
            "wed",
            "thu",
            "fri",
            "sat"
        ];
        var monthDirection = 0;

        function getNextMonth() {
            monthDirection++;
            var current;
            var now = new Date();
            if (now.getMonth() == 11) {
                current = new Date(now.getFullYear(), now.getMonth() + monthDirection, 1);
                // current = new Date(now.getFullYear() + monthDirection, 0, 1);
            } else {
                current = new Date(now.getFullYear(), now.getMonth() + monthDirection, 1);
            }
            initCalender(getMonth(current));
        }

        function getPrevMonth() {
            monthDirection--;
            var current;
            var now = new Date();
            if (now.getMonth() == 11) {
                current = new Date(now.getFullYear(), now.getMonth() + monthDirection, 1);
                // current = new Date(now.getFullYear() + monthDirection, 0, 1);
            } else {
                current = new Date(now.getFullYear(), now.getMonth() + monthDirection, 1);
            }
            initCalender(getMonth(current));
        }
        Date.prototype.isSameDateAs = function (pDate) {
            return (
                this.getFullYear() === pDate.getFullYear() &&
                this.getMonth() === pDate.getMonth() &&
                this.getDate() === pDate.getDate()
            );
        };

        function getMonth(currentDay) {
            var now = new Date();
            var currentMonth = month[currentDay.getMonth()];
            var monthArr = [];
            for (i = 1 - currentDay.getDate(); i < 31; i++) {
                var tomorrow = new Date(currentDay);
                tomorrow.setDate(currentDay.getDate() + i);
                if (currentMonth !== month[tomorrow.getMonth()]) {
                    break;
                } else {
                    monthArr.push({
                        date: {
                            weekday: weekday[tomorrow.getDay()],
                            weekday_short: weekdayShort[tomorrow.getDay()],
                            day: tomorrow.getDate(),
                            month: month[tomorrow.getMonth()],
                            year: tomorrow.getFullYear(),
                            current_day: now.isSameDateAs(tomorrow) ? true : false,
                            date_info: tomorrow
                        }
                    });
                }
            }
            return monthArr;
        }

        function clearCalender() {

            var tr = document.querySelectorAll('table tbody tr');

            tr.forEach(element => {
                element.querySelectorAll('td').forEach(function(td) {
                    td.classList.remove('active', 'selectable', 'currentDay', 'between', 'hover');
                    td.innerHTML = '';
                })
            })

            document.querySelectorAll("td").forEach(function (td) {
                td.removeEventListener('mouseenter', null);
                td.removeEventListener('mouseleave', null);
                td.removeEventListener('click', null);
                // $(this).unbind('mouseenter').unbind('mouseleave');
            });

            clickCounter = 0;
        }

        function initCalender(monthData) {
            var row = 0;
            var classToAdd = "";
            var currentDay = "";
            var today = new Date();

            clearCalender();
            // var i = 0;
            monthData.forEach(function (value) {
                var weekday = value.date.weekday_short;
                var day = value.date.day;
                var column = 0;

                document.querySelector(".sideb .header .month").innerHTML = value.date.month;
                document.querySelector(".sideb .header .year").innerHTML = value.date.year;


                if (value.date.current_day) {
                    currentDay = "currentDay";
                    classToAdd = "selectable";
                }
                if (today.getTime() < value.date.date_info.getTime()) {
                    classToAdd = "selectable";

                }
                document.querySelectorAll("tr.weedays th").forEach(function (th) {
                    if (th.dataset.weekday === weekday) {
                        column = th.dataset.column;
                        return;
                    }
                });

                if(classToAdd.length) {
                    document.querySelectorAll("tr.days")[row].querySelectorAll('td')[column].classList.add(classToAdd);
                }

                if(currentDay.length) {
                    document.querySelectorAll("tr.days")[row].querySelectorAll('td')[column].classList.add(currentDay);
                }

                document.querySelectorAll("tr.days")[row].querySelectorAll('td')[column].innerHTML = day;

                currentDay = "";
                if (column == 6) {
                    row++;
                }
            });

            document.querySelectorAll("td.selectable")
                .forEach(td => {
                    td.addEventListener('click', () => {
                        document.querySelectorAll('td.selectable').forEach(function(td) {
                            td.classList.remove('active', 'between', 'hover');
                            // td.classList.remove('active', 'between', 'hover', 'currentDay');
                        })
                        td.classList.toggle('active');
                    })
                })
        }
        initCalender(getMonth(new Date()));

        document.querySelector(".fa-angle-left").onclick = () => getPrevMonth();
        document.querySelector(".fa-angle-right").onclick = () => getNextMonth();

    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
    <script>
        var courses_count = @json(count($courses->courses));
        var progress = @json(getReportNumber($complete_courses,0));
        var not_complete = @json(getReportNumber($complete_courses,2));
        var complete = @json(getReportNumber($complete_courses,1));

        var oilCanvas = document.getElementById("oilChart");

        Chart.defaults.global.defaultFontFamily = "Lato";
        Chart.defaults.global.defaultFontSize = 14;

        var oilData = {
            labels: [
                // "My Courses",
                "In Progess",
                "Not Started",
                "Completed",
            ],
            datasets: [
                {
                    // data: [courses_count, progress, not_complete, complete],
                    data: [progress, not_complete, complete],
                    backgroundColor: [
                        // "#303d47",
                        "#00bcb3",
                        "#eaeaea",
                        "#fb4400",
                    ]
                }]
        };

        var pieChart = new Chart(oilCanvas, {
            type: 'pie',
            data: oilData
        });

    </script>
{{--    <script>--}}
{{--        var ctx = document.getElementById('myChart')--}}
{{--        // eslint-disable-next-line no-unused-vars--}}
{{--        var myChart = new Chart(ctx, {--}}
{{--            type: 'line',--}}
{{--            data: {--}}
{{--                labels: [--}}
{{--                    'Jan',--}}
{{--                    'Feb',--}}
{{--                    'Mar',--}}
{{--                    'Apr',--}}
{{--                    'May',--}}
{{--                    'Jun',--}}
{{--                    'Jul ',--}}
{{--                    'Aug',--}}
{{--                    'Sep',--}}
{{--                    'Oct',--}}
{{--                    'Nov',--}}
{{--                    'Dec',--}}
{{--                ],--}}
{{--                datasets: [{--}}
{{--                    data: [100, 50, 20, 155, 20, 33, 75, 88, 45, 90, 10, 50],--}}
{{--                    lineTension: 0,--}}
{{--                    backgroundColor: 'transparent',--}}
{{--                    borderColor: '#D1D1D1',--}}
{{--                    borderWidth: 2,--}}
{{--                    pointBackgroundColor: '#fb4400'--}}
{{--                }]--}}
{{--            },--}}
{{--            options: {--}}
{{--                scales: {--}}
{{--                    xAxes: [{--}}
{{--                        gridLines: {--}}
{{--                            display: false--}}
{{--                        }--}}
{{--                    }],--}}
{{--                    yAxes: [{--}}
{{--                        display: false,--}}
{{--                    }]--}}
{{--                },--}}
{{--                legend: {--}}
{{--                    display: false--}}
{{--                }--}}
{{--            }--}}
{{--        })--}}
{{--    </script>--}}
@endsection


