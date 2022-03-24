@extends('layouts.app')

@section('useHead')
    <title>{{$content->course->trans_title}} {{__('education.Files')}} | {{ __('home.DC_title') }}</title>
@endsection

@section('style')
    <link rel="stylesheet" href="https://cdn.plyr.io/3.6.12/plyr.css" />

@endsection

@section('content')
    <?php
        $NextPrevNavigation = \App\Helpers\CourseContentHelper::NextPrevNavigation($next, $previous);
        $next_url = $NextPrevNavigation['next_url'];
        $previous_url = $NextPrevNavigation['previous_url'];
    ?>

    <div class="user-info">
        @if($popup_compelte_status)
            <div class="custom-model-main custom-model-main-cert model-open">
                <div class="custom-model-inner">
                    <div class="custom-model-wrap">
                        <div class="close-btn">×</div>
                        <div class="pop-up-content-wrap">
                            <div class="congrats">
                                <div class="text-center course-image">
                                    <div class="no-img certificate-img" style="display:flex; align-items: center; justify-content: center;">
                                        <img src="{{CustomAsset('icons/certificate.svg')}}" height="auto" width="30%">
                                    </div>
                                    <div>
                                        <h1>Congratulations!</h1>
                                        <p>
                                            You have successfully completed the course. Can’t wait for to hear the good news about you getting certified! <br><br>
                                            Good Luck in your exam
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-overlay"></div>
            </div>
        @endif

        @if($popup_gift_status)
            <div class="custom-model-main custom-model-main-gift model-open">
                <div class="custom-model-inner">
                    <div class="custom-model-wrap">
                        <div class="close-btn">×</div>
                        <div class="pop-up-content-wrap">
                            <div class="congrats">
                                <div class="text-center course-image">
                                    <div class="no-img certificate-img" style="display:flex; align-items: center; justify-content: center;">
                                        <img src="{{CustomAsset('icons/open.svg')}}" height="auto" width="30%">
                                    </div>
                                    <div>
                                        <h1>Woohoo, Congrats!</h1>
                                        <p>
                                            We’re glad you made it. Enjoy your gift now!
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-overlay"></div>
            </div>
        @endif

        <div class="dash-header course_info">
            @include('pages.templates.breadcrumb', [
                'course_id'=>$content->course->id,
                'course_title'=>$content->course->trans_title,
                'section_title' => $content->section->title,
                'content_title'=>$content->title,
            ])
            <br>
            {{-- <h1 style="text-transform:capitalize;">{{ $content->course->trans_title }}</h1> --}}
        </div>
        <h3 style="text-transform:capitalize; display: none;" class="title_file_new">{{ $content->title }}</h3>

        <div class="row mx-0 file">
            @if(session()->has('status'))
                {{-- <div style="background: #fb4400;color: #fff; padding: 20px;font-size: 1rem">{{session()->get('msg')}}</div> --}}
                <div class="container">
                    <div class="row">
                        <div class="error-notice">
                            <div class="oaerror danger">
                                {{-- <strong>Error</strong>- --}}
                                {{session()->get('msg')}}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="d-flex">
                        <h3 class="m-0 title_file_old" style="text-transform:capitalize;margin-right: 10px!important;">{{ $content->title }}</h3>
                        <span class="flag @if($flag != 0 ) flag-mark @endif" style="width: 25px;cursor: pointer" onclick="saveFlag({{$content->id}})">
                            @if(file_exists(public_path('icons/file_flag_old.svg')))
                                {!!  file_get_contents(public_path('icons/file_flag_old.svg'))  !!}
                            @else
                                Flag
                            @endif
                    </span>
                        @if($content->downloadable==1)
                            <?php
                            $folder_name = '';
                            switch ($content->post_type){
                                case 'video': $folder_name = 'videos'; break;
                                case 'audio': $folder_name = 'audios'; break;
                                case 'presentation': $folder_name = 'presentations'; break;
                                default : $folder_name = '';
                            }
                            ?>
                            <a href="{{CustomAsset('upload/files/'.$folder_name.'/'.$content->upload->file)}}" download>
                                <img style="filter: opacity(1);margin-right: 5px;" width="28.126" height="28.127" src="{{CustomAsset('icons/download.svg')}}" alt="{{$content->title}}">
                            </a>
                        @endif
                    </div>


                    @include('Html.next-prev-navigation', [
                        'next'=>$next,
                        'previous'=>$previous,
                        'previous_url'=>$previous_url,
                    ])
                </div>
                <div class="card-body">
                    @isset($content->upload->file)
                        @if($content->post_type == 'video' )
                            {{--                        <video  playsinline controls class="video w-100" preload="metadata"  controlsList="nodownload" id="player">--}}
                            {{--                            <source id="update_video_source" src="" type="video/mp4" />--}}
                            {{--                            Your browser does not support the video tag.--}}
                            {{--                        </video>--}}

                            <video  playsinline controls class="video w-100" preload="metadata"  controlsList="nodownload" id="player">
                                <source src="{{CustomAsset('upload/files/videos/'.$content->upload->file)}}" type="video/mp4" />
                                Your browser does not support the video tag.
                            </video>

                        @elseif($content->post_type == 'audio' )
                            <audio controls>
                                <source src="{{CustomAsset('upload/files/audios/'.$content->upload->file)}}">
                            </audio>
                        @elseif($content->post_type == 'presentation' )
                            @if($content->upload->extension == 'jpeg' || $content->upload->extension ==  'png' )
                                <img  src="{{CustomAsset('upload/files/presentations/'.$content->upload->file)}}">
                            @elseif($content->upload->extension == 'pdf' )

                                {{--<style>
                                #google-pdf-viewer {
                                    border: 1px solid #cccccc;
                                    width: 100%;
                                    height: 600px;
                                }
                                @media (max-width: 767.98px) {

                                    #google-pdf-viewer {
                                        height: 554px;
                                    }
                                }
                                .output {
                                    background: #eee;
                                }
                                </style>
                                <iframe id="google-pdf-viewer" style="" title="{{$content->title}}" sandbox="allow-scripts allow-same-origin allow-orientation-lock allow-top-navigation"
                                src='https://docs.google.com/viewer?&amp;embedded=true&url={{CustomAsset('upload/files/presentations/'.$content->upload->file)}}' ></iframe>
                                --}}
                                {{-- <embed width="100%" height="600px" id="update_file_source" src='' > --}}
                                {{-- <iframe width="100%" height="600px" id="update_file_source" src='' style="border: 1px solid #eaeaea;" ></iframe> --}}
                                {{-- @include('Html.PDF.container', ['file'=>$content->upload->file??null]) --}}
                                {{-- @include('Html.PDF.pdf', ['file'=>$content->upload->file??null]) --}}
                                {{-- <script src="https://documentcloud.adobe.com/view-sdk/main.js"></script> --}}
                                @include('Html.PDF.adobe', [
                                    'file'=>CustomAsset('upload/files/presentations/'.$content->upload->file),
                                    'title'=>$content->title??null,
                                ])
                            @elseif($content->upload->extension == 'xls' )
                                <a href='{{CustomAsset('upload/files/presentations/'.$content->upload->file)}}'>{{$content->title}}</a>
                            @else
                                <iframe style="" width="100%" height="600px"   src='https://view.officeapps.live.com/op/embed.aspx?src={{CustomAsset('upload/files/presentations/'.$content->upload->file)}}' ></iframe>
                            @endif

                        @elseif($content->post_type == 'scorm' )
                            @if($content->upload->extension == 'pdf' )
                                <iframe width="100%" height="600px" id="update_file_source" src='' ></iframe>
                            @else
                                <?php
                                $SCOInstanceID = ScormId($content->id);
                                ?>
                                <iframe src="{{CustomAsset('vsscorm/api.php')}}?SCOInstanceID={{$SCOInstanceID}}&content_id={{$content->id}}&course_id={{$content->course->id}}&user_id={{auth()->user()->id}}" name="API" style="display: none;"></iframe>
                                @if(file_exists( public_path('upload/files/scorms/'.str_replace('.zip', '', $content->upload->file).'/scormdriver/indexAPI.html') ))
                                    <iframe src="{{CustomAsset('upload/files/scorms/'.str_replace('.zip', '', $content->upload->file).'/scormdriver/indexAPI.html')}}" name="course" style="display: block; width:100%;height:700px;border:none;"></iframe>
                                @elseif(file_exists( public_path('upload/files/scorms/'.str_replace('.zip', '', $content->upload->file).'/interaction_html5.html') ))
                                    <iframe src="{{CustomAsset('upload/files/scorms/'.str_replace('.zip', '', $content->upload->file).'/interaction_html5.html')}}" name="course" style="display: block; width:100%;height:700px;border:none;"></iframe>
                                @else
                                    <iframe src="{{CustomAsset('upload/files/scorms/'.str_replace('.zip', '', $content->upload->file).'/index_lms.html')}}" name="course" style="display: block; width:100%;height:700px;border:none;"></iframe>
                                @endif

                            @endif
                        @else
                            <iframe style="" width="100%" height="600px"  src='https://view.officeapps.live.com/op/embed.aspx?src={{CustomAsset('upload/files/files/'.$content->upload->file)}}' ></iframe>
                        @endif

                    @endisset
                    {{--                    <iframe id="update_file_source" style="" width="100%" height="600px"  src='' ></iframe>--}}


                </div>
            </div>
        </div>
    </div>

@endsection

@section("script")

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    @if($popup_compelte_status || $popup_gift_status)
        <script>
            $(".close-btn, .bg-overlay").click(function(){
                $(".custom-model-main-gift").removeClass('model-open');
                $(".custom-model-main-cert").removeClass('model-open');
            });
        </script>
    @endif

    <script>
        // save Flag
        function saveFlag(content_id){
            $('.flag').toggleClass('flag-mark')

            $.post("{{route("user.flag_content")}}",
                {
                    content_id : content_id,
                    _token : "{{csrf_token()}}"

                },
                function(data, status){
                    // console.log(data)
                })
        }


        document.getElementById("demo").innerHTML = "Next";

        function NextBtn(){
            document.querySelector(".next").addEventListener("click", function(event){
                window.location.href = '{{$next_url??null}}'
            });
        }
        NextBtn();
    </script>


    @isset($content->upload->file)
        @if($content->post_type == 'video' )
            <script src="https://cdn.plyr.io/3.6.12/plyr.js"></script>
            <script>
                // video player
                new Plyr('#player');
            </script>
        @endif
    @endisset



    {{--@isset($content->upload->file)--}}
    {{--    @if($content->post_type == 'video' )--}}
    {{--        <script src="https://cdn.plyr.io/3.6.12/plyr.js"></script>--}}
    {{--        <script>--}}

    {{--        // video player--}}
    {{--        new Plyr('#player');--}}


    {{--        // Select the source and video tags--}}
    {{--        const player = document.querySelector("#update_video_source");--}}
    {{--        const vid = player.parentElement;--}}

    {{--        let video_id = {{$content->upload->id}};  // Getting the selected video id, it depends on your code--}}
    {{--        let user_id = {{ auth()->id() }} // It depends on your code too--}}

    {{--        fetch('{{url("video")}}/' +--}}
    {{--            video_id +--}}
    {{--            "&&" +--}}
    {{--            user_id,--}}
    {{--            {--}}
    {{--                method: "GET",--}}
    {{--                headers: {--}}
    {{--                    "Content-Type": "application/json",--}}
    {{--                },--}}
    {{--            }--}}
    {{--        ) .then((x) => x.json())--}}
    {{--            .then( (x) => {--}}
    {{--                player.setAttribute("src", x.url+"#t=0.2");--}}
    {{--                vid.load();--}}
    {{--            })--}}


    {{--        //////////////--}}

    {{--        vid.addEventListener("error", ()=>{--}}
    {{--            fetch(--}}
    {{--                "/video" +--}}
    {{--                '{{url("video")}}/' +--}}
    {{--                video_id +--}}
    {{--                "&&" +--}}
    {{--                user_id,--}}
    {{--                {--}}
    {{--                    method: "GET",--}}
    {{--                    headers: {--}}
    {{--                        "Content-Type": "application/json",--}}
    {{--                    },--}}
    {{--                }--}}
    {{--            )--}}
    {{--                .then((x) => x.json())--}}
    {{--                .then((x) => {--}}
    {{--                    let ct = vid.currentTime;--}}
    {{--                    player.setAttribute("src", x.url+"#t=0.2");--}}
    {{--                    vid.load();--}}
    {{--                    vid.addEventListener(--}}
    {{--                        "loadedmetadata",--}}
    {{--                        function() {--}}
    {{--                            this.currentTime = ct;--}}
    {{--                        },--}}
    {{--                        false--}}
    {{--                    );--}}
    {{--                    vid.play();--}}
    {{--                });--}}
    {{--        });--}}
    {{--    </script>--}}
    {{--    @endif--}}

    {{--    @if( ($content->post_type == 'presentation' || $content->post_type == 'scorm') && $content->upload->extension == 'pdf')--}}
    {{--      <script>--}}

    {{--        // Select the source and video tags--}}
    {{--        const iframe_el = document.querySelector("#update_file_source");--}}
    {{--        const file = iframe_el.parentElement;--}}

    {{--        let file_id = {{$content->upload->id}};  // Getting the selected video id, it depends on your code--}}
    {{--        let user_id = {{ auth()->id() }}        // It depends on your code too--}}
    {{--        var post_type = "{{$content->post_type}}";--}}
    {{--        fetch('{{url("file")}}/' +--}}
    {{--                file_id +--}}
    {{--                "&&" +--}}
    {{--                user_id +--}}
    {{--                "&&" +--}}
    {{--               post_type--}}
    {{--            ,--}}
    {{--                {--}}
    {{--                    method: "GET",--}}
    {{--                    headers: {--}}
    {{--                        "Content-Type": "application/json",--}}
    {{--                    },--}}
    {{--                }--}}
    {{--            ) .then((x) => x.json())--}}
    {{--                .then( (x) => {--}}
    {{--                    iframe_el.setAttribute("src", x.url+"#toolbar=0");--}}
    {{--                })--}}


    {{--        //////////////--}}

    {{--        file.addEventListener("error", ()=>{--}}
    {{--            fetch(--}}
    {{--                "/file" +--}}
    {{--                '{{url("file")}}/' +--}}
    {{--                file_id +--}}
    {{--                "&&" +--}}
    {{--                user_id,--}}
    {{--                {--}}
    {{--                    method: "GET",--}}
    {{--                    headers: {--}}
    {{--                        "Content-Type": "application/json",--}}
    {{--                    },--}}
    {{--                }--}}
    {{--            )--}}
    {{--                .then((x) => x.json())--}}
    {{--                .then((x) => {--}}
    {{--                    let ct = file.currentTime;--}}
    {{--                    iframe_el.setAttribute("src", x.url+"#toolbar=0");--}}
    {{--                    file.addEventListener(--}}
    {{--                        "loadedmetadata",--}}
    {{--                        function() {--}}
    {{--                            this.currentTime = ct;--}}
    {{--                        },--}}
    {{--                        false--}}
    {{--                    );--}}
    {{--                });--}}
    {{--        });--}}
    {{--    </script>--}}
    {{--    @endif--}}
    {{--@endisset--}}
    <script>
        var enabled = "{{$enabled}}";

        let svg_time =
            `
                <svg xmlns="http://www.w3.org/2000/svg" style="width: auto; height: 16px; margin-left: 5px;" viewBox="0 0 34.151 35.854">
                    <g id="Group_122" data-name="Group 122" transform="translate(-1085.293 -313.029)"><g id="Group_121" data-name="Group 121" transform="translate(1085.293 313.029)"><g id="Group_108" data-name="Group 108" transform="translate(19.269 20.974)"><path id="Path_117" data-name="Path 117" d="M1110.64,352.445v-1.3a10.538,10.538,0,0,0,10.527-10.526h1.3A11.84,11.84,0,0,1,1110.64,352.445Z" transform="translate(-1110.64 -340.618)" fill="#575756"></path></g> <g id="Group_109" data-name="Group 109" transform="translate(16.13 17.836)"><path id="Path_118" data-name="Path 118" d="M1109.65,342.771a3.14,3.14,0,1,1,3.141-3.14A3.143,3.143,0,0,1,1109.65,342.771Zm0-4.979a1.839,1.839,0,1,0,1.84,1.84A1.842,1.842,0,0,0,1109.65,337.792Z" transform="translate(-1106.51 -336.491)" fill="#575756"></path></g> <g id="Group_110" data-name="Group 110" transform="translate(15.623 22.712)"><rect id="Rectangle_72" data-name="Rectangle 72" width="3.198" height="1.301" transform="matrix(0.548, -0.836, 0.836, 0.548, 0, 2.674)" fill="#575756"></rect></g> <g id="Group_111" data-name="Group 111" transform="translate(20.106 12.488)"><rect id="Rectangle_73" data-name="Rectangle 73" width="7.246" height="1.301" transform="translate(0 6.06) rotate(-56.746)" fill="#575756"></rect></g> <g id="Group_112" data-name="Group 112" transform="translate(4.387 2.785)"><path id="Path_119" data-name="Path 119" d="M1105.946,349.762a14.779,14.779,0,0,1-5.873-1.2l.514-1.2a13.583,13.583,0,1,0,7.132-25.948.651.651,0,0,1-.566-.645v-2.776h-2.413v2.776a.651.651,0,0,1-.566.645,13.606,13.606,0,0,0-11.808,13.465,13.8,13.8,0,0,0,.214,2.416l-1.281.228a15.088,15.088,0,0,1-.234-2.644,14.913,14.913,0,0,1,12.375-14.67v-2.867a.649.649,0,0,1,.65-.65h3.714a.651.651,0,0,1,.651.65v2.867a14.882,14.882,0,0,1-2.508,29.552Z" transform="translate(-1091.064 -316.693)" fill="#575756"></path></g> <g id="Group_113" data-name="Group 113" transform="translate(15.054)"><path id="Path_120" data-name="Path 120" d="M1112.875,317.116h-7.129a.651.651,0,0,1-.651-.651v-2.786a.65.65,0,0,1,.651-.65h7.129a.651.651,0,0,1,.651.65v2.786A.652.652,0,0,1,1112.875,317.116Zm-6.479-1.3h5.829V314.33H1106.4Z" transform="translate(-1105.095 -313.029)" fill="#575756"></path></g> <g id="Group_114" data-name="Group 114" transform="translate(8.281 7.553)"><rect id="Rectangle_74" data-name="Rectangle 74" width="1.301" height="2.435" transform="matrix(0.803, -0.596, 0.596, 0.803, 0, 0.776)" fill="#575756"></rect></g> <g id="Group_115" data-name="Group 115" transform="translate(7.23 6.538)"><rect id="Rectangle_75" data-name="Rectangle 75" width="2.953" height="1.301" transform="matrix(0.803, -0.596, 0.596, 0.803, 0, 1.76)" fill="#575756"></rect></g> <g id="Group_116" data-name="Group 116" transform="translate(27.834 7.553)"><rect id="Rectangle_76" data-name="Rectangle 76" width="2.435" height="1.301" transform="matrix(0.596, -0.803, 0.803, 0.596, 0, 1.955)" fill="#575756"></rect></g> <g id="Group_117" data-name="Group 117" transform="translate(28.235 6.539)"><rect id="Rectangle_77" data-name="Rectangle 77" width="1.301" height="2.953" transform="translate(0 1.044) rotate(-53.414)" fill="#575756"></rect></g> <g id="Group_118" data-name="Group 118" transform="translate(1.922 24.822)"><rect id="Rectangle_78" data-name="Rectangle 78" width="6.776" height="1.301" fill="#575756"></rect></g> <g id="Group_119" data-name="Group 119" transform="translate(7.575 29.158)"><rect id="Rectangle_79" data-name="Rectangle 79" width="4.716" height="1.301" fill="#575756"></rect></g> <g id="Group_120" data-name="Group 120" transform="translate(0 32.522)"><rect id="Rectangle_80" data-name="Rectangle 80" width="8.701" height="1.301" fill="#575756"></rect></g></g></g>
                </svg>
            `;
        let svg_next =
            `
                <svg id="Group_104" data-name="Group 104" xmlns="http://www.w3.org/2000/svg" width="14.836" height="24.835" viewBox="0 0 14.836 24.835">
                    <path id="Path_99" data-name="Path 99" d="M149.351,218.961a1.511,1.511,0,0,0,1.02-.4l11.823-10.909a1.508,1.508,0,0,0,0-2.215l-11.823-10.912a1.508,1.508,0,0,0-2.045,2.215l10.625,9.8-10.625,9.8a1.508,1.508,0,0,0,1.025,2.616Z" transform="translate(-147.843 -194.126)" fill="#fff"/>
                </svg>
            `;

        if(!enabled){
            document.getElementById("demo").addEventListener("click", function(event){
                event.preventDefault()
            });
            window.onload = function(){
                var start_time = "{{$content->time_limit}}";
                let t = new Date();
                t = new Date(t.getTime() + (start_time * 1000));
                var countDownDate = t.getTime();


                document.querySelector(".next").insertAdjacentHTML('beforeend', svg_time);

                var x = setInterval(function() {
                    var now = new Date().getTime();
                    var distance = countDownDate - now;
                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    document.getElementById("demo").innerHTML = hours + "h "
                        + minutes + "m " + seconds + "s ";

                    // document.getElementById("demo").nextElementSibling.



                    if (distance < 0) {
                        clearInterval(x);

                        $.post("{{route("user.update_completed_status")}}",
                            {
                                content_id: {{$content->id}},
                                _token: "{{csrf_token()}}"

                            },
                            function(data, status){
                                console.log(data)
                            });


                        document.getElementById("demo").innerHTML = "Next";
                        let el = document.getElementById('demo').nextElementSibling.remove();

                        document.querySelector(".next").insertAdjacentHTML('beforeend', svg_next);

                        {{--document.querySelector(".next").addEventListener("click", function(event){
                            window.location.href = '{{$next_url??null}}'
                        });--}}
                        NextBtn();
                    }

                }, 1000);
            }

        }else{
            document.getElementById("demo").innerHTML = "Next";
            document.querySelector(".next").insertAdjacentHTML('beforeend', svg_next);

            {{--document.querySelector(".next").addEventListener("click", function(event){
                window.location.href = '{{$next_url??null}}'
            });--}}
            NextBtn();
        }
    </script>
@endsection


