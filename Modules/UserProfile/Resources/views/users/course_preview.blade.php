@extends(FRONT.'.education.layouts.master')

@section('useHead')
    <title>{{__('education.My Courses')}} | {{ __('home.DC_title') }}</title>
@endsection
@section('content')

    <div class="userarea-wrapper"  >
        <div class="row no-gutters">
            @include('userprofile::users.sidebar')
            <div class="col-md-9 col-lg-10">
                <div class="main-user-content m-4">
                    <div class="card p-5 user-info">
                        <h4 class="mb-4"><i class="fas fa-graduation-cap"></i> {{ __('education.My Courses') }}</h4>
                        @if($content->post_type == 'video' )

                               @isset($content->upload->file)
                                     <video controls>
                                         <source src="{{CustomAsset('upload/files/videos/'.$content->upload->file)}}">
                                     </video>
                                 @elseif($content->url)
                                    <iframe style="height:400px"  frameborder='0' src='{{$content->url}}' >
                                @endisset

                        @elseif($content->post_type == 'audio' )
                                     @isset($content->upload->file)
                                            <audio controls>
                                                  <source src="{{CustomAsset('upload/files/audios/'.$content->upload->file)}}">
                                              </audio>
                                     @endisset
                        @elseif($content->post_type == 'presentation' )
                               @isset($content->upload->file)
                                       <iframe style="height:400px"  frameborder='0' src='https://view.officeapps.live.com/op/embed.aspx?src={{CustomAsset('upload/files/presentations/'.$content->upload->file)}}' >
                              @endisset
                        @elseif($content->post_type == 'scorm' )
                           @isset($content->upload->file)
                              <iframe style="height:400px"  frameborder='0' src='https://view.officeapps.live.com/op/embed.aspx?src={{CustomAsset('upload/files/scorms/'.$content->upload->file)}}' >
                          @endisset
                        @else
                                 @isset($content->upload->file)
                                <iframe style="height:400px"  frameborder='0' src='https://view.officeapps.live.com/op/embed.aspx?src={{CustomAsset('upload/files/files/'.$content->upload->file)}}' >
                               @endisset
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
