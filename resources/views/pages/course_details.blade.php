@extends('layouts.app')
@section('useHead')
<title>{{$course->trans_title}} | {{ __('home.DC_title') }}</title>
@endsection
<style>
.section-title {
    font-size: 15px !important;
    letter-spacing: .1rem;
}
#main-vue-element .course-image {
    /* padding-top: 25px; */
}
.progress-main {
    width: 60% !important;
    margin-top: 15px !important;
}
svg {
    fill: #c6c6c6;
    cursor: pointer;
    width: 20px;
}
.certification-card span {
    color: rgb(106, 106, 106) !important;
}
@media (max-width: 576px){
    .progress-main {
        width: 100% !important;
    }
}

.svghover {
    fill: #fb4400;
}
</style>

@section('content')
<?php
   $video = null;
   $image = null;
   if($course->uploads){
       foreach ($course->uploads as $file){
           if($file->post_type == 'intro_video' ){
               $video = $file;
           }else if($file->post_type == 'image'){
               $image = $file;
           }
       }
   }

    $src_flag = CustomAsset('icons/flag.svg');
    $src_flag_focus = CustomAsset('icons/flag_focus.svg');

   ?>
{{--
<div class="dash-header course_info">
   @include('pages.templates.breadcrumb', [
   'course_id'=>$course->id,
   'course_title'=>$course->trans_title,
   ])
   <br>
</div>
--}}

<div class="course_details">
    <div class="dash-header course-header d-flex align-items-md-end flex-column flex-md-row px-3">
        <div class="text-center course-image w-30 mb-4 mt-2 mb-md-0">
            <?php
                $url = '';
                if($course->upload != null) {
                    // if ($url == ''){
                    //     $url = 'https://ui-avatars.com/api/?background=fb4400&color=fff&name=' . auth()->user()->trans_name;
                    // }else{
                        $url = $course->upload->file;
                        $url = CustomAsset('upload/full/'. $url);
                    // }
                }else {
                    $url = 'https://ui-avatars.com/api/?background=6a6a6a&color=fff&name=' . $course->trans_title;
                }
                ?>
            @if($image != null)
            <div class="image">
                <img src="{{CustomAsset('upload/thumb200/'.$course->upload->file)}}" height="auto" width="auto">
            </div>
            @else
            <div class="image no-img">
                <img src="{{CustomAsset($url)}}" height="auto" width="100px">
            </div>
            @endif
            <div class="progress">
                <div style="width: {{$course->users[0]->pivot->progress??0}}% !important;" class="bar"></div>
            </div>
            <small>{{$course->users[0]->pivot->progress??0}}% Complete</small>
        </div>
        <div class="mx-md-4 course_info">
            <h1 style="text-transform: capitalize;">{{$course->trans_title}}</h1>
            @if($course->PDUs > 0)
            <span class="pdu">
            {{$course->PDUs}} PDUs
            </span>
            @endif
            <div class="rating">
                <span class="total_rate" v-text="total_rate"></span>
                <template v-for="item in 5">
                    <template v-if="item <= stars">
                    <svg xmlns="http://www.w3.org/2000/svg" width="17.43" height="16.6"
                        viewBox="0 0 17.43 16.6">
                        <path id="Path_39" data-name="Path 39"
                            d="M88.211,199.955l-5.375-2.706-5.4,2.66.915-5.948-4.2-4.313,5.938-.966,2.805-5.326,2.753,5.35,5.934,1.018L87.348,194Z"
                            transform="translate(-74.153 -183.355)" fill="#fb4400" />
                    </svg>
                    </template>
                    <template v-if="item > stars && (item == half_star)">
                    <svg xmlns="http://www.w3.org/2000/svg" id="Group_32" data-name="Group 32"  width="17.43"
                        height="16.6" viewBox="0 0 17.43 16.6">
                        <path id="Path_43" data-name="Path 43"
                            d="M160.391,199.955l-5.375-2.706-5.394,2.66.91-5.948-4.2-4.313,5.938-.966,2.805-5.326,2.758,5.35,5.929,1.018L159.528,194Z"
                            transform="translate(-146.334 -183.355)" fill="#c6c6c6" />
                        <path id="Path_44" data-name="Path 44"
                            d="M155.025,183.4l-2.753,5.228-5.938.966,4.2,4.313-.91,5.948,5.394-2.66.009,0Z"
                            transform="translate(-146.334 -183.298)" fill="#fb4400" />
                    </svg>
                    </template>
                    <template v-if="item > stars && (item != (half_star))">
                    <svg xmlns="http://www.w3.org/2000/svg" width="17.43" height="16.6"
                        viewBox="0 0 17.43 16.6">
                        <path id="Path_42" data-name="Path 42"
                            d="M142.346,199.955l-5.375-2.706-5.4,2.66.915-5.948-4.2-4.313,5.938-.966,2.8-5.326,2.753,5.35,5.934,1.018L141.483,194Z"
                            transform="translate(-128.289 -183.355)" fill="#c6c6c6" />
                    </svg>
                    </template>
                </template>
            </div>

            <div class="d-flex">
                <li class="has-dropdown user course-details" style="list-style: none; margin-right: 5px;">
                    <a onclick="event.stopPropagation();this.nextElementSibling.classList.toggle('d-none'); return false;" class="main-button main-color review" href="#">
                    {{__('education.Add a Review')}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="10.125" height="6.382" viewBox="0 0 10.125 6.382">
                        <path id="Path_114" data-name="Path 114" d="M6.382,5.063,0,0V10.125Z"
                            transform="translate(10.125) rotate(90)" fill="#fb4400" />
                    </svg>
                    </a>
                    <div class="dropdown d-none" style="left: 0; min-width:auto; width: max-content !important;">
                        <div class="p-2">
                           <template v-for="(item, index) in 5">
                              <span @click="review(item)" v-if="item <= rate">
                                 <svg :id="index" onmouseleave="mouseleave(this.id)" onmouseover="svghover(this.id)" xmlns="http://www.w3.org/2000/svg" width="18%" height="20"
                                    viewBox="0 0 17.43 16.6">
                                    <path id="Path_39" data-name="Path 39"
                                       d="M88.211,199.955l-5.375-2.706-5.4,2.66.915-5.948-4.2-4.313,5.938-.966,2.805-5.326,2.753,5.35,5.934,1.018L87.348,194Z"
                                       transform="translate(-74.153 -183.355)" fill="#fb4400" />
                                 </svg>
                              </span>
                              <span @click="review(item)" v-if="item > rate">
                                 <svg :id="index" onmouseleave="mouseleave(this.id)" onmouseover="svghover(this.id)" xmlns="http://www.w3.org/2000/svg" width="18%" height="20"
                                    viewBox="0 0 17.43 16.6">
                                    {{-- id="Path_42"  --}}
                                    <path data-name="Path 42"
                                       MessageController d="M142.346,199.955l-5.375-2.706-5.4,2.66.915-5.948-4.2-4.313,5.938-.966,2.8-5.326,2.753,5.35,5.934,1.018L141.483,194Z"
                                       transform="translate(-128.289 -183.355)" />
                                 </svg>
                              </span>
                           </template>
                        </div>
                     </div>
                </li>
                @if(!is_null($course->users[0]->pivot->progress))
                <a href="{{route("user.resume",$course->id)}}" class="main-button main-color">Resume Course</a>
                @endif
            </div>
        </div>
    </div>

    @if ($course->trans_excerpt)
        <div class="row mx-0 my-4">
            @if($video)
                <div class="col-lg-9 col-xl-9 course_info">
                    <p class="lead light">{{$course->trans_excerpt}}</p>
                </div>
                <div class="col-lg-3 col-xl-3">
                    <div class="card h-100 justify-content-center align-items-center p-3 video-btn">
                        <video width="100%" oncontextmenu="return false;" controls="controls" controlslist="nodownload" preload="metadata" class="embed-responsive-item">
                            <source src="{{CustomAsset('upload/video/'.$video->file)}}#t=0.2" type="video/mp4">
                        </video>
                    </div>
                </div>
            @else
                <div class="col-lg-12 col-xl-12 course_info">
                    <p class="lead light card p-5">{{$course->trans_excerpt}}</p>
                </div>
            @endif
        </div>
    @endif

    @if (count($course->contents) > 0)
    <div class="row mx-0 mt-3 course-content">
    <div class="col-12 course_info">
        <h3>{{__('education.Materials')}}</h3>
    </div>
    <div class="col-lg-8 mb-5 mb-lg-0 course_info">
        @foreach($course->contents as $key => $section)
        <div class="card learning-file mb-3">
            <h3>{{$section->title}}</h3>
            <div style="margin: 0px 40px;">{!!  $section->details->excerpt??null !!}</div>
            @isset($section->contents)
            <ul>
                @foreach($section->contents as $k => $content)

                <li>
                {{-- <div v-if=>
                    <img  style="width:20px;"  src="{{$src_flag}}">
                </div>
                <div @if($content->flag == 1) @endif>
                    <img  style="width:20px;"  src="{{$src_flag_focus}}">
                </div> --}}

                <a @if( ( isset($content->user_contents[0]) )  || ($content->status == 1)  )     href=" @if($content->post_type != 'exam') {{CustomRoute('user.course_preview',$content->id)}} @else {{CustomRoute('user.exam',$content->id)}} @endif" @else style="color: #c1bebe" href="#"  onclick="return false"  @endif >
                <img style="filter: opacity(0.7);" width="28.126" height="28.127" src="{{CustomAsset('icons/'.$content->post_type.'.svg')}}" alt="Kiwi standing on oval">
                <span> {{$content->title}}</span>
                @if(isset($content->user_contents[0]) && $content->user_contents[0]->pivot->is_completed == 1)
                    <span class="svg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="52" height="52" viewBox="0 0 52 52">
                            <path id="Path" d="M0,24.5A24.5,24.5,0,1,0,24.5,0,24.5,24.5,0,0,0,0,24.5Z" transform="translate(1.5 1.5)" fill="#fff" stroke="#4cdd42" stroke-width="3" stroke-dasharray="0 0"/>
                            <path id="Path-2" data-name="Path" d="M10.516,15.62a2.042,2.042,0,0,1-2.879,0L.491,8.474A2.042,2.042,0,0,1,3.37,5.6l5.707,5.7L19.887.491A2.042,2.042,0,0,1,22.766,3.37h0Z" transform="translate(14.372 17.946)" fill="#4cdd42"/>
                        </svg>
                    </span>
                @else
                    <span class="svg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="52" height="52" viewBox="0 0 59 59">
                            <g id="Check_2" data-name="Check 2" transform="translate(-0.2 0.2)">
                            <rect id="Check_2_Background_" data-name="Check 2 (Background)" width="59" height="59" transform="translate(0.2 -0.2)" fill="none"/>
                            <path id="_22_Check" data-name="22 Check" d="M0,24.5A24.5,24.5,0,1,0,24.5,0,24.5,24.5,0,0,0,0,24.5Z" transform="translate(5.2 4.8)" fill="none" stroke="#d7d7d7" stroke-width="2" stroke-dasharray="0 0"/>
                            </g>
                        </svg>
                    </span>
                @endif
                </a>
                </li>
                @endforeach
            </ul>
            @endisset
        </div>
        <!-- /.learning-file -->
        @endforeach
    </div>
    <div class="col-lg-4 course_info">
        @if(isset($course->users[0]->pivot->progress) && ($course->users[0]->pivot->progress >= $course->complete_progress ) )

        {{-- class="green mb-1"  --}}
        <a href="{{route('training.certificates.certificate_dynamic', ['course_registration_id'=> $course_registration_id ] )}}"
            target="_blank">
        <div class="text-center course-image certificate certification-card">
            <div class="no-img certificate-img" style="display:flex; align-items: center; justify-content: center;">
                <img src="{{CustomAsset('icons/certificate.svg')}}" height="auto" width="30%">
            </div>
            <div>
                <h2>Congratulations!</h2>
                <span>You have successfully completed the course. </span>
            </div>
        </div>
        </a>
        @endif

        @include('Html.activity-card', [
            'activities'=>$activities,
            'cls'=>'card p-30 activity',
        ])
        {{--<div class="card p-30 learning-file activity" style="padding: 0 !important;">
            <h3>{{__('education.Activity Completed')}}</h3>
            <ul style="list-style: none; padding: 0;">
                <php $lang = app()->getLocale(); ?>
                @foreach($activities as $activity)
                <li>
                @if($activity->type == 'exam')
                    <a style="color: #6a6a6a !important;" href="{{ CustomRoute('user.exam',$activity->content_id)}}">
                    <img class="activity_icon" src="{{CustomAsset('icons/activity.svg')}}" alt="activity_icon">
                    <span>{{$activity->content_title}} - ({{ json_decode($activity->course_title)->$lang }})</span>
                    </a>
                @else
                    <a style="color: #6a6a6a !important;" href="{{ CustomRoute('user.course_preview',$activity->content_id)}}">
                    <img class="activity_icon" src="{{CustomAsset('icons/activity.svg')}}" alt="activity_icon">
                    <span>{{$activity->content_title}} - ({{ json_decode($activity->course_title)->$lang }})</span>
                    </a>
                @endif
                </li>
                @endforeach
            </ul>
        </div>--}}
    </a>
    @endif
</div>
@endsection
@section('script')
<script>
   window.total_rate = {!! json_encode($total_rate) !!}
   window.rate =  @json($course->course_rate->rate)


   new Vue({
   'el' : '#main-vue-element',
   'data' : {
   total_rate: window.total_rate,
   stars : 0,
   half_star  : 0,
   rate  : window.rate,
   },
   created(){
   this.getTotalRate()
   this.setTotalRateStars()
   },
   methods : {
   getTotalRate : function(){
   this.total_rate = parseFloat(this.total_rate).toFixed(2);
   return this.total_rate;
   },
   setTotalRateStars : function(){
   this.half_star = 0;
   let total_as_int = parseInt(this.total_rate)
   this.stars = total_as_int
   if(total_as_int < this.total_rate){
   this.half_star = this.stars + 1
   }
   },
   review : function (item) {
   // alert(item)
   let self = this;
   var data = {
   'course_id' : {{$course->id}},
   'rate' : item
   }

   this.rate = item
   axios.post("{{route('user.rate')}}",
   data
   )
   .then(response => {
   self.total_rate =  response.data.data;
   self.getTotalRate();
   self.setTotalRateStars();
   console.log(response.data)
   })
   .catch(e => {
   console.log(e)
   });



   {{--$.ajax({--}}
   {{--    type: "POST",--}}
   {{--    url: @json(CustomRoute('user.rate')),--}}
   {{--    data: {--}}
   {{--        'course_id' : {{$course->id}},--}}
   {{--        '_token' : @json(csrf_token()),--}}
   {{--        'rate' : rate--}}
   {{--    },--}}
   {{--    success: function(response){--}}
   {{--        console.log(response)--}}
   {{--        $('.total_rate').text(parseFloat(response.data).toFixed(1))--}}
   {{--        total_review()--}}
   {{--    },--}}
   {{--});--}}



   }
   }
   });
</script>
@if($video)
<script>
   var btn = document.querySelector('.video-btn');
   var modal = document.querySelector('.modal');
   var content = document.querySelector('.modal-content');
   var close = document.querySelector('.modal-close');

   btn.onclick = e => {
   modal.querySelector('video').play();
   modal.classList.add("show");
   };

   close.onclick = e => {
   modal.querySelector('video').pause();
   modal.classList.remove("show");
   };

   modal.onclick = e => {
   modal.querySelector('video').pause();
   modal.classList.remove("show");
   };

   content.onclick = e => e.stopPropagation()
</script>
@endif

<script>
    function svghover(id){
        for(i=0; i<=id; i++){
            document.getElementById(i).classList.add("svghover");
        }
    }
    function mouseleave(id){
        for(i=0; i<5; i++){
            document.getElementById(i).classList.remove("svghover");
        }
    }
</script>
@endsection
