@extends('layouts.app')

@section('useHead')
    <title>{{__('education.My Courses')}} | {{ __('home.DC_title') }}</title>
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
    <div class="dash-header">
        @include('pages.templates.breadcrumb', [
            'course_id'=>$content->course->id,
            'course_title'=>$content->course->trans_title,
            'content_title'=>$content->title,
        ])
        <br>
        {{-- <h1 style="text-transform:capitalize;">{{ $content->course->trans_title }}</h1> --}}
    </div>

    <div class="row mt-3">

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="m-0" style="text-transform:capitalize;">{{ $content->course->trans_title }}</h3>
                <div class="d-flex align-items-center">
                    @if($previous)
                        <button onclick="location.href =  '{{$previous_url}}'"><svg id="Group_103" data-name="Group 103" xmlns="http://www.w3.org/2000/svg" width="14.836" height="24.835" viewBox="0 0 14.836 24.835">
                            <path id="Path_99" data-name="Path 99" d="M161.171,218.961a1.511,1.511,0,0,1-1.02-.4l-11.823-10.909a1.508,1.508,0,0,1,0-2.215l11.823-10.912a1.508,1.508,0,0,1,2.045,2.215l-10.625,9.8,10.625,9.8a1.508,1.508,0,0,1-1.025,2.616Z" transform="translate(-147.843 -194.126)" fill="#fff"/>
                            </svg>
                            </button>
                    @endif
                    <span class="mx-1 mx-sm-3" style="text-transform:capitalize;">{{ $content->title }}</span>

                        @if($next)
                        <button onclick="location.href = '{{$next_url}}'"><svg id="Group_104" data-name="Group 104" xmlns="http://www.w3.org/2000/svg" width="14.836" height="24.835" viewBox="0 0 14.836 24.835">
                            <path id="Path_99" data-name="Path 99" d="M149.351,218.961a1.511,1.511,0,0,0,1.02-.4l11.823-10.909a1.508,1.508,0,0,0,0-2.215l-11.823-10.912a1.508,1.508,0,0,0-2.045,2.215l10.625,9.8-10.625,9.8a1.508,1.508,0,0,0,1.025,2.616Z" transform="translate(-147.843 -194.126)" fill="#fff"/>
                            </svg>
                            </button>
                    @endif
                </div>
            </div>
            <div class="card-body p-30">
{{--                            <iframe height="700" width="100%" src="https://bakkah.com/public/upload/pdf/2021-09-28-03-49-18-en_pdf-مجلة بكه للتعليم العدد 7.pdf" frameborder="0"></iframe>--}}
                <!-- <iframe style="height:400px"  frameborder='0' src='https://view.officeapps.live.com/op/embed.aspx?src=https://bakkah.com/public/upload/pdf/2021-09-28-03-49-18-en_pdf-مجلة بكه للتعليم العدد 7.pdf' > -->


                @isset($content->upload->file)
                    @if($content->post_type == 'video' )
                        <video controls class="w-100">
                            <source src="{{CustomAsset('upload/files/videos/'.$content->upload->file)}}">
                        </video>
                    @elseif($content->post_type == 'audio' )
                        <audio controls>
                            <source src="{{CustomAsset('upload/files/audios/'.$content->upload->file)}}">
                        </audio>
                    @elseif($content->post_type == 'presentation' )
                        @if($content->upload->extension == 'pdf' )
                            <iframe width="100%" height="500px"
                                    src='{{CustomAsset('upload/files/presentations/'.$content->upload->file)}}' ></iframe>
                        @else
                            <iframe style="" width="100%" height="500px"   src='https://view.officeapps.live.com/op/embed.aspx?src={{CustomAsset('upload/files/presentations/'.$content->upload->file)}}' ></iframe>
                        @endif

                    @elseif($content->post_type == 'scorm' )
                        @if($content->upload->extension == 'pdf' )
                            <iframe width="100%" height="500px"
                            src='{{CustomAsset('upload/files/scorms/'.$content->upload->file)}}' ></iframe>
                        @else
                            <?php
                            $user_id = sprintf("%'.09d", auth()->user()->id);
                            $content_id = sprintf("%'.09d", $content->id);
                            $SCOInstanceID = '1'.$user_id.'2'.$content_id;
                            ?>
                            <iframe src="{{CustomAsset('vsscorm/api.php')}}?SCOInstanceID={{$SCOInstanceID}}" name="API" style="display: none;"></iframe>
                            <iframe src="{{CustomAsset('upload/files/scorms/'.str_replace('.zip', '', $content->upload->file).'/scormdriver/indexAPI.html')}}" name="course" style="display: block; width:100%;height:700px;border:none;"></iframe>
                            {{-- <iframe src="{{CustomAsset('scorm/scormdriver/indexAPI.html')}}" name="course" style="display: block; width:100%;height:700px;border:none;"></iframe> --}}
                            {{-- @include('scorm') --}}
                            {{-- <iframe style="" width="100%" height="500px" src='https://view.officeapps.live.com/op/embed.aspx?src={{CustomAsset('upload/files/scorms/'.$content->upload->file)}}' ></iframe> --}}
                        @endif
                    @else
                        <iframe style="" width="100%" height="500px"  src='https://view.officeapps.live.com/op/embed.aspx?src={{CustomAsset('upload/files/files/'.$content->upload->file)}}' ></iframe>
                    @endif


                @endisset

                @if($content->post_type == 'video' && $content->url)
                    <?php
                    if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $content->url, $match)) {
                        $video_id = $match[1]??null;
                    }
                    ?>
                    <iframe style="" width="100%" height="500px" allowfullscreen="" src='https://www.youtube.com/embed/{{$video_id??null}}' ></iframe>
                @endif
            </div>
        </div>
    </div>
@endsection
