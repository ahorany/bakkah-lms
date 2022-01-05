@extends('layouts.app')

@section('useHead')
    <title>{{$content->course->trans_title}} {{__('education.Files')}} | {{ __('home.DC_title') }}</title>
@endsection

@section('style')
    <link rel="stylesheet" href="https://cdn.plyr.io/3.6.12/plyr.css" />

    <style>

        .custom-model-main {
            text-align: center;
            overflow: hidden;
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0; /* z-index: 1050; */
            -webkit-overflow-scrolling: touch;
            outline: 0;
            opacity: 0;
            -webkit-transition: opacity 0.15s linear, z-index 0.15;
            -o-transition: opacity 0.15s linear, z-index 0.15;
            transition: opacity 0.15s linear, z-index 0.15;
            z-index: -1;
            overflow-x: hidden;
            overflow-y: auto;
        }

        .model-open {
            z-index: 99999;
            opacity: 1;
            overflow: hidden;
        }
        .custom-model-inner {
            -webkit-transform: translate(0, -25%);
            -ms-transform: translate(0, -25%);
            transform: translate(0, -25%);
            -webkit-transition: -webkit-transform 0.3s ease-out;
            -o-transition: -o-transform 0.3s ease-out;
            transition: -webkit-transform 0.3s ease-out;
            -o-transition: transform 0.3s ease-out;
            transition: transform 0.3s ease-out;
            transition: transform 0.3s ease-out, -webkit-transform 0.3s ease-out;
            display: inline-block;
            vertical-align: middle;
            width: 600px;
            margin: 30px auto;
            max-width: 97%;
        }
        .custom-model-wrap {
            display: block;
            width: 100%;
            position: relative;
            background-color: #fff;
            border: 1px solid #999;
            border: 1px solid rgba(0, 0, 0, 0.2);
            border-radius: 6px;
            -webkit-box-shadow: 0 3px 9px rgba(0, 0, 0, 0.5);
            box-shadow: 0 3px 9px rgba(0, 0, 0, 0.5);
            background-clip: padding-box;
            outline: 0;
            text-align: left;
            padding: 20px;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            max-height: calc(100vh - 70px);
            overflow-y: auto;
        }
        .model-open .custom-model-inner {
            -webkit-transform: translate(0, 0);
            -ms-transform: translate(0, 0);
            transform: translate(0, 0);
            position: relative;
            z-index: 999;
        }
        .model-open .bg-overlay {
            background: rgba(0, 0, 0, 0.6);
            z-index: 99;
        }
        .bg-overlay {
            background: rgba(0, 0, 0, 0);
            height: 100vh;
            width: 100%;
            position: fixed;
            left: 0;
            top: 0;
            right: 0;
            bottom: 0;
            z-index: 0;
            -webkit-transition: background 0.15s linear;
            -o-transition: background 0.15s linear;
            transition: background 0.15s linear;
        }
        .close-btn {
            position: absolute;
            right: 0;
            top: -30px;
            cursor: pointer;
            z-index: 99;
            font-size: 30px;
            color: #fff;
        }

        @media screen and (min-width:800px){
            .custom-model-main:before {
                content: "";
                display: inline-block;
                height: auto;
                vertical-align: middle;
                margin-right: -0px;
                height: 100%;
            }
        }
        @media screen and (max-width:799px){
            .custom-model-inner{margin-top: 45px;}
        }

    </style>
@endsection

@section('content')
    <?php
    if( !is_null($next)){
        if( $next->post_type != 'exam' ) {
            $next_url = CustomRoute('user.course_preview',$next->id);
        }else{
            $next_url =  CustomRoute('user.exam',$next->id);
        }
    }

    if(!is_null($previous)){
        if($previous->post_type != 'exam'){
            $previous_url = CustomRoute('user.course_preview',$previous->id);
        }else{
            $previous_url =  CustomRoute('user.exam',$previous->id);
        }
    }
    ?>

    @if($popup_compelte_status)
        <div class="custom-model-main model-open">
            <div class="custom-model-inner">
                <div class="close-btn">×</div>
                <div class="custom-model-wrap">
                    <div class="pop-up-content-wrap">
                        Content Here
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

    <div class="row mt-3">
        @if(session()->has('status'))
            <div style="background: #fb4400;color: #fff; padding: 20px;font-size: 1rem">{{session()->get('msg')}}</div>
        @endif
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="m-0" style="text-transform:capitalize;">{{ $content->title }}</h3>
                <div class="d-flex align-items-center">

{{--                    <div class="title" style="margin-right: 15px;">--}}
{{--                        <span class="previous-title">--}}
{{--                            <a id="title-prev" style="display: none; color: #9c9c9c;" href="{{$previous_url}}">({{$previous->title}})</a>--}}
{{--                        </span>--}}
{{--                        <span class="next-title">--}}
{{--                            <a id="title-next" style="display: none; color: #9c9c9c;" href="{{$next_url}}">({{$next->title}})</a>--}}
{{--                        </span>--}}
{{--                    </div>--}}

                    @if($previous)
                    {{-- <span class="previous-title"><a style="color: #9c9c9c;" href="{{$previous_url}}">({{$previous->title}})</a></span> --}}
                        <button title="{{$previous->title}}" class="next_prev" onclick="location.href =  '{{$previous_url}}'">
                            <svg id="Group_103" data-name="Group 103" xmlns="http://www.w3.org/2000/svg" width="14.836" height="24.835" viewBox="0 0 14.836 24.835">
                                <path id="Path_99" data-name="Path 99" d="M161.171,218.961a1.511,1.511,0,0,1-1.02-.4l-11.823-10.909a1.508,1.508,0,0,1,0-2.215l11.823-10.912a1.508,1.508,0,0,1,2.045,2.215l-10.625,9.8,10.625,9.8a1.508,1.508,0,0,1-1.025,2.616Z" transform="translate(-147.843 -194.126)" fill="#fff"/>
                            </svg>
                            <span>{{__('education.Previous')}}</span>
                        </button>
                    @endif


                    @if($next)
                        <button title="{{$next->title}}"  class="next next_prev">
                            <span id="demo">{{__('education.Next')}}</span>
                            <svg id="Group_104" data-name="Group 104" xmlns="http://www.w3.org/2000/svg" width="14.836" height="24.835" viewBox="0 0 14.836 24.835">
                               <path id="Path_99" data-name="Path 99" d="M149.351,218.961a1.511,1.511,0,0,0,1.02-.4l11.823-10.909a1.508,1.508,0,0,0,0-2.215l-11.823-10.912a1.508,1.508,0,0,0-2.045,2.215l10.625,9.8-10.625,9.8a1.508,1.508,0,0,0,1.025,2.616Z" transform="translate(-147.843 -194.126)" fill="#fff"/>
                           </svg>
                        </button>
                            {{-- <span class="next-title"><a style="color: #9c9c9c;" href="{{$next_url}}">({{$next->title}})</a></span> --}}
                    @endif
                </div>


            </div>
            <div class="card-body">
                @isset($content->upload->file)
                    @if($content->post_type == 'video' )
                        <video  playsinline controls class="video w-100" preload="metadata"  controlsList="nodownload" id="player">
                            <source id="update_video_source" src="" type="video/mp4" />
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
                            {{-- <embed width="100%" height="600px" id="update_file_source" src='' > --}}
                            {{-- <iframe width="100%" height="600px" id="update_file_source" src='' style="border: 1px solid #eaeaea;" ></iframe> --}}
                            @include('Html.PDF.container', ['file'=>$content->upload->file??null])

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
                            $user_id = sprintf("%'.05d", auth()->user()->id);
                            $content_id = sprintf("%'.05d", $content->id);
                            $SCOInstanceID = (1).$user_id.(2).$content_id;
                            ?>
                            <iframe src="{{CustomAsset('vsscorm/api.php')}}?SCOInstanceID={{$SCOInstanceID}}&user_id={{auth()->user()->id}}" name="API" style="display: none;"></iframe>
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
@endsection

@section("script")

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    @if($popup_compelte_status)
    <script>
        $(".close-btn, .bg-overlay").click(function(){
            $(".custom-model-main").removeClass('model-open');
        });
    </script>
    @endif

     <script>
        document.getElementById("demo").innerHTML = "Next";
        document.querySelector(".next").addEventListener("click", function(event){
        window.location.href = '{{$next_url??null}}'
        });
    </script>
@isset($content->upload->file)
    @if($content->post_type == 'video' )
        <script src="https://cdn.plyr.io/3.6.12/plyr.js"></script>
        <script>

        // video player
        new Plyr('#player');


        // Select the source and video tags
        const player = document.querySelector("#update_video_source");
        const vid = player.parentElement;

        let video_id = {{$content->upload->id}};  // Getting the selected video id, it depends on your code
        let user_id = {{ auth()->id() }} // It depends on your code too

        fetch('{{url("video")}}/' +
            video_id +
            "&&" +
            user_id,
            {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                },
            }
        ) .then((x) => x.json())
            .then( (x) => {
                player.setAttribute("src", x.url+"#t=0.2");
                vid.load();
            })


        //////////////

        vid.addEventListener("error", ()=>{
            fetch(
                "/video" +
                '{{url("video")}}/' +
                video_id +
                "&&" +
                user_id,
                {
                    method: "GET",
                    headers: {
                        "Content-Type": "application/json",
                    },
                }
            )
                .then((x) => x.json())
                .then((x) => {
                    let ct = vid.currentTime;
                    player.setAttribute("src", x.url+"#t=0.2");
                    vid.load();
                    vid.addEventListener(
                        "loadedmetadata",
                        function() {
                            this.currentTime = ct;
                        },
                        false
                    );
                    vid.play();
                });
        });
    </script>
    @endif

    @if( ($content->post_type == 'presentation' || $content->post_type == 'scorm') && $content->upload->extension == 'pdf')
      <script>

        // Select the source and video tags
        const iframe_el = document.querySelector("#update_file_source");
        const file = iframe_el.parentElement;

        let file_id = {{$content->upload->id}};  // Getting the selected video id, it depends on your code
        let user_id = {{ auth()->id() }} // It depends on your code too
        var post_type = "{{$content->post_type}}";
        fetch('{{url("file")}}/' +
                file_id +
                "&&" +
                user_id +
                "&&" +
               post_type
            ,
                {
                    method: "GET",
                    headers: {
                        "Content-Type": "application/json",
                    },
                }
            ) .then((x) => x.json())
                .then( (x) => {
                    iframe_el.setAttribute("src", x.url+"#toolbar=0");
                })


        //////////////

        file.addEventListener("error", ()=>{
            fetch(
                "/file" +
                '{{url("file")}}/' +
                file_id +
                "&&" +
                user_id,
                {
                    method: "GET",
                    headers: {
                        "Content-Type": "application/json",
                    },
                }
            )
                .then((x) => x.json())
                .then((x) => {
                    let ct = file.currentTime;
                    iframe_el.setAttribute("src", x.url+"#toolbar=0");
                    file.addEventListener(
                        "loadedmetadata",
                        function() {
                            this.currentTime = ct;
                        },
                        false
                    );
                });
        });
    </script>
    @endif
@endisset
     <script>
        var enabled = "{{$enabled}}";

        if(!enabled){
            document.getElementById("demo").addEventListener("click", function(event){
                event.preventDefault()
            });
            window.onload = function(){
            var start_time = "{{$content->time_limit}}";
            let t = new Date();
            t = new Date(t.getTime() + (start_time * 1000));
            var countDownDate = t.getTime();

            var x = setInterval(function() {
                var now = new Date().getTime();
                var distance = countDownDate - now;
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                document.getElementById("demo").innerHTML = hours + "h "
                    + minutes + "m " + seconds + "s ";

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
                    document.querySelector(".next").addEventListener("click", function(event){
                        window.location.href = '{{$next_url??null}}'
                    });
                }

            }, 1000);
        }



        }else{
          document.getElementById("demo").innerHTML = "Next";
            document.querySelector(".next").addEventListener("click", function(event){
                window.location.href = '{{$next_url??null}}'
            });
        }


    </script>
@endsection


