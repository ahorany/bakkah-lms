@extends(FRONT.'.education.layouts.master')

@section('useHead')
    <title>{{__('education.My Courses')}} | {{ __('home.DC_title') }}</title>
@endsection

@section('content')
<style>
.userarea-wrapper{
    background: #fafafa;
}
.file .card-title {
    background: #848484;
    color: #fff;
}
.arrow i {
    border: 1px solid;
    border-radius: 50%;
    padding: 5px 10px;
    text-align: center;
    margin: 0 5px;
    font-size: 20px;
    cursor:pointer;
}
.arrow i:hover {
    color: #fb4400;
}
</style>
    <div class="userarea-wrapper">
        <div class="row no-gutters">
            @include('userprofile::users.sidebar')
            <div class="col-md-9 col-lg-10">
                <div class="main-user-content m-4">
                    <div class="p-5 user-info file"  style="width:100%; margin:0 auto">
                        <small>Dashboard / My Course / {{ $content->course->trans_title }}</small>
                        <h1 style="font-weight: 700; margin: 5px 0 10px;">{{ $content->course->trans_title }}</h1>
{{--                        <p>PDF File</p>--}}
{{--                        <!-- <iframe src="https://docs.google.com/gview?url={{CustomAsset('upload/pdf/slides.pdf')}}" style="" width="100%" height="500px" allowfullscreen="" webkitallowfullscreen=""></iframe> -->--}}

                        <div class="card" style="width: 100%; border-radius: 10px; border: 1px solid #d6d6d6; overflow: hidden;">
                            <div class="card-title px-5 py-3">
                                <div class="row">
                                    <div class="col-md-12 col-lg-12 col-12">
                                        <h3>{{ $content->title }}</h3>
                                    </div>
                                </div>
                            </div>


                            <div class="card-body py-0">

                                 @isset($content->upload->file)
                                        @if($content->post_type == 'video' )
                                                <video controls>
                                                    <source src="{{CustomAsset('upload/files/videos/'.$content->upload->file)}}">
                                                </video>
                                         @elseif($content->post_type == 'audio' )
                                                 <audio controls>
                                                      <source src="{{CustomAsset('upload/files/audios/'.$content->upload->file)}}">
                                                </audio>
                                            @elseif($content->post_type == 'presentation' )
                                                  <iframe style="" width="100%" height="500px"   src='https://view.officeapps.live.com/op/embed.aspx?src={{CustomAsset('upload/files/presentations/'.$content->upload->file)}}' ></iframe>
                                            @elseif($content->post_type == 'scorm' )
                                                 <iframe style="" width="100%" height="500px" src='https://view.officeapps.live.com/op/embed.aspx?src={{CustomAsset('upload/files/scorms/'.$content->upload->file)}}' ></iframe>
                                            @else
                                                <iframe style="" width="100%" height="500px"  src='https://view.officeapps.live.com/op/embed.aspx?src={{CustomAsset('upload/files/files/'.$content->upload->file)}}' ></iframe>
                                        @endif

                                    @endisset

                                        @if($content->post_type == 'video' && $content->url)
                                            <?php
                                                 if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $content->url, $match)) {
                                                     $video_id = $match[1]??null;
//                                                     dd($video_id);
                                                 }
                                            ?>


                                               <iframe style="" width="100%" height="500px" allowfullscreen="" src='https://www.youtube.com/embed/{{$video_id??null}}' ></iframe>
                                        @endif




                                        {{--                                <iframe src="https://www.alwatanvoice.com/arabic/index.html"  webkitallowfullscreen=""></iframe>--}}
                            </div>


                            <div class="arrow text-center py-3">


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

{{--                                <span class="num">2 / 9</span>--}}
                                @if($previous)
                                        <i onclick="location.href =  '{{$previous_url}}'" class="fas fa-angle-left"></i>
                                @endif

                                @if($next)
                                    <i onclick="location.href = '{{$next_url}}'  " class="fas fa-angle-right"></i>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
