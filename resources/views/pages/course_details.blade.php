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
    padding-top: 25px;
}
.progress-main {
    width: 60% !important;
    margin-top: 15px !important;
}
svg {
    fill: #c6c6c6;
    cursor: pointer;
}
@media (max-width: 576px){
    .progress-main {
        width: 100% !important;
    }
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
<<<<<<< HEAD
    <div class="dash-header course-header d-flex align-items-md-end flex-column flex-md-row">
    <div class="text-center course-image w-30 mb-4 mb-md-0">
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
        @if($course->users[0]->pivot->progress >= $course->complete_progress)
            <a href="{{route('training.certificates.certificate_dynamic', ['course_registration_id'=> $course_registration_id ] )}}" class="green mb-1" target="_blank">
            Certificate
            </a>
        @endif
        <div class="d-flex">
            <li class="has-dropdown user course-details" style="list-style: none; margin-right: 5px;">
                <a onclick="event.stopPropagation();this.nextElementSibling.classList.toggle('d-none'); return false;" class="main-button main-color review" href="#">
                {{__('education.Add a Review')}}
                <svg xmlns="http://www.w3.org/2000/svg" width="10.125" height="6.382" viewBox="0 0 10.125 6.382">
                    <path id="Path_114" data-name="Path 114" d="M6.382,5.063,0,0V10.125Z"
                        transform="translate(10.125) rotate(90)" fill="#fb4400" />
                </svg>
                </a>
                <div class="dropdown d-none" style="left: 0; width: max-content !important;">
                <div class="p-2">
                    <template v-for="item in 5">
                        <span @click="review(item)" v-if="item <= rate">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18%" height="20"
                            viewBox="0 0 17.43 16.6">
                            <path id="Path_39" data-name="Path 39"
                                d="M88.211,199.955l-5.375-2.706-5.4,2.66.915-5.948-4.2-4.313,5.938-.966,2.805-5.326,2.753,5.35,5.934,1.018L87.348,194Z"
                                transform="translate(-74.153 -183.355)" fill="#fb4400" />
                            </svg>
                        </span>
                        <span @click="review(item)" v-if="item > rate">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18%" height="20"
                            viewBox="0 0 17.43 16.6">
                            <path id="Path_42" data-name="Path 42"
                                MessageController            d="M142.346,199.955l-5.375-2.706-5.4,2.66.915-5.948-4.2-4.313,5.938-.966,2.8-5.326,2.753,5.35,5.934,1.018L141.483,194Z"
                                transform="translate(-128.289 -183.355)" fill="#c6c6c6" />
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
    <div class="col-lg-8 col-xl-9 course_info">
        <p class="lead light">{{$course->trans_excerpt}}</p>
    </div>
    @if($video)
    <div class="col-lg-4 col-xl-3">
        <div class="card h-100 justify-content-center align-items-center p-3 video-btn">
            <video width="100%" oncontextmenu="return false;" controls="controls" controlslist="nodownload" preload="metadata" class="embed-responsive-item">
                <source src="{{CustomAsset('upload/video/'.$video->file)}}#t=0.2" type="video/mp4">
            </video>
            {{--
            <button>
                <svg xmlns="http://www.w3.org/2000/svg" width="26.818" height="30.542"
                viewBox="0 0 26.818 30.542">
                <path id="Path_92" data-name="Path 92" d="M1586.871,1164.139V1133.6l26.818,15.165Z"
                    transform="translate(-1586.871 -1133.597)" fill="#fff" />
                </svg>
            </button>
            --}}
        </div>
    </div>
    @endif
    </div>
    @endif
    {{-- @if($video)
    <div class="modal">
    <div class="modal-content">
        <div class="modal-close">x</div>
        <video width="100%" oncontextmenu="return false;" controls="controls" controlslist="nodownload" src="{{CustomAsset('upload/video/'.$video->file)}}" class="embed-responsive-item"></video>
    </div>
    </div>
    @endif --}}
    @if (count($course->contents) > 0)
    <div class="row mx-0 mt-3 course-content">
    <div class="col-12 course_info">
        <h3>CONTENT</h3>
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
                <a @if( ( isset($content->user_contents[0]) )  || ($content->status == 1)  )     href=" @if($content->post_type != 'exam') {{CustomRoute('user.course_preview',$content->id)}} @else {{CustomRoute('user.exam',$content->id)}} @endif" @else style="color: #c1bebe" href="#"  onclick="return false"  @endif >
                <img style="filter: opacity(0.7);" width="28.126" height="28.127" src="{{CustomAsset('icons/'.$content->post_type.'.svg')}}" alt="Kiwi standing on oval">
                <span> {{$content->title}}</span>
                {{-- @if ($content->role_and_path == 1)
                <div class="req">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve">
                        <path class="st0" d="M3.05,37.53c0.07-0.06,0.17-0.1,0.19-0.17c0.84-2.4,2.63-3.44,5.08-3.65c6.42-0.54,12.83-1.13,19.25-1.7  c2.1-0.19,4.19-0.4,6.29-0.56c0.63-0.05,0.94-0.33,1.18-0.9c3.42-8.06,6.86-16.11,10.32-24.15c1.42-3.29,5.37-4.2,7.97-1.87  c0.68,0.61,1.09,1.38,1.45,2.2c3.36,7.88,6.74,15.76,10.08,23.65c0.33,0.77,0.76,1.02,1.54,1.09c6.66,0.58,13.32,1.19,19.97,1.8  c2.01,0.18,4.01,0.36,6.02,0.56c2.17,0.22,3.64,1.41,4.29,3.45c0.66,2.06,0.09,3.88-1.52,5.3c-4.97,4.39-9.96,8.75-14.94,13.12  c-1.58,1.39-3.15,2.8-4.77,4.15c-0.53,0.45-0.68,0.85-0.52,1.55c1.95,8.44,3.85,16.89,5.78,25.33c0.75,3.3-1.07,6.03-4.35,6.43  c-1.24,0.15-2.33-0.27-3.38-0.9c-7.3-4.38-14.62-8.73-21.91-13.14c-0.78-0.47-1.32-0.49-2.11-0.01c-7.31,4.42-14.66,8.79-22,13.17  c-2.68,1.6-5.61,0.98-7.13-1.46c-0.76-1.23-0.91-2.57-0.59-3.97c1.91-8.39,3.8-16.77,5.74-25.15c0.21-0.92,0.04-1.45-0.68-2.08  c-6.32-5.49-12.59-11.03-18.91-16.52c-1.09-0.95-1.9-2.03-2.34-3.39C3.05,38.99,3.05,38.26,3.05,37.53z"/>
                    </svg>
                </div>
                @endif --}}
                @if(isset($content->user_contents[0]) && $content->user_contents[0]->pivot->is_completed == 1)
                <span class="svg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="71.3" height="62.387" viewBox="0 0 71.3 62.387">
                        <path id="Icon_open-task" data-name="Icon open-task" d="M0,0V62.387H62.387v-32L53.475,39.3V53.475H8.912V8.912h32L49.821,0ZM62.387,0,35.65,26.737l-8.912-8.912-8.912,8.912L35.65,44.562,71.3,8.912Z" fill="#dcdcdcb3"></path>
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
=======
<div class="dash-header course-header d-flex align-items-md-end flex-column flex-md-row">
   <div class="text-center course-image w-30 mb-4 mb-md-0">
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
      <div class="progress progress-main">
         <div style="width: {{$course->users[0]->pivot->progress??0}}% !important;" class="bar"></div>
      </div>
      <small>{{$course->users[0]->pivot->progress??0}}% Complete</small>
   </div>
   <div class="mx-md-4 course_info">
      <h1 class="h1-course-title" style="text-transform: capitalize; font-weight:normal;">{{$course->trans_title}}</h1>
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
      @if($course->users[0]->pivot->progress >= $course->complete_progress)
        {{--<a href="{{route('training.certificates.certificate_dynamic', ['course_registration_id'=> $course_registration_id ] )}}" class="green mb-1" target="_blank">
        Certificate
        </a>--}}
      @endif
      <div class="d-flex">
         <li class="has-dropdown user course-details" style="list-style: none; margin-right: 5px;">
            <a onclick="event.stopPropagation();this.nextElementSibling.classList.toggle('d-none'); return false;" class="main-button main-color review" href="#">
               {{__('education.Add a Review')}}
               <svg xmlns="http://www.w3.org/2000/svg" width="10.125" height="6.382" viewBox="0 0 10.125 6.382">
                  <path id="Path_114" data-name="Path 114" d="M6.382,5.063,0,0V10.125Z"
                     transform="translate(10.125) rotate(90)" fill="#5D5B5A" />
               </svg>
            </a>
            <div class="dropdown d-none" style="left: 0; width: max-content !important;">
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
   <div class="col-lg-8 col-xl-9 course_info">
      <p class="lead light">{{$course->trans_excerpt}}</p>
   </div>
   @if($video)
   <div class="col-lg-4 col-xl-3">
      <div class="card h-100 justify-content-center align-items-center p-3 video-btn">
         <video width="100%" oncontextmenu="return false;" controls="controls" controlslist="nodownload" preload="metadata" class="embed-responsive-item">
            <source src="{{CustomAsset('upload/video/'.$video->file)}}#t=0.2" type="video/mp4">
         </video>
         {{--
         <button>
            <svg xmlns="http://www.w3.org/2000/svg" width="26.818" height="30.542"
               viewBox="0 0 26.818 30.542">
               <path id="Path_92" data-name="Path 92" d="M1586.871,1164.139V1133.6l26.818,15.165Z"
                  transform="translate(-1586.871 -1133.597)" fill="#fff" />
            </svg>
         </button>
         --}}
      </div>
   </div>
   @endif
</div>
@endif
{{-- @if($video)
<div class="modal">
   <div class="modal-content">
      <div class="modal-close">x</div>
      <video width="100%" oncontextmenu="return false;" controls="controls" controlslist="nodownload" src="{{CustomAsset('upload/video/'.$video->file)}}" class="embed-responsive-item"></video>
   </div>
</div>
@endif --}}
@if (count($course->contents) > 0)
<div class="row mx-0 mt-3 course-content">
   <div class="col-12 course_info">
      <h3>CONTENT</h3>
   </div>
   <div class="col-lg-8 mb-5 mb-lg-0 course_info">
      @foreach($course->contents as $key => $section)
      <div class="card learning-file mb-3">
         <h3 class="section-title">{{$section->title}}</h3>
         <div style="margin: 0px 40px;">{!!  $section->details->excerpt??null !!}</div>
         @isset($section->contents)
         <ul>
            @foreach($section->contents as $k => $content)
            <li>
               <a @if( ( isset($content->user_contents[0]) )  || ($content->status == 1)  )
                href=" @if($content->post_type != 'exam')

                @if($content->downloadable==1)
                    {{CustomAsset('upload/files/presentations/'.$content->upload->file)}}
                @else
                    {{CustomRoute('user.course_preview',$content->id)}}
                @endif
                @else {{CustomRoute('user.exam',$content->id)}}
                @endif" @else style="color: #c1bebe" href="#"  onclick="return false"  @endif >

                <img style="filter: opacity(0.7);" width="28.126" height="28.127" src="{{CustomAsset('icons/'.$content->post_type.'.svg')}}" alt="Kiwi standing on oval">
                <span> {{$content->title}}</span>
                {{-- @if ($content->role_and_path == 1)
                <div class="req">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve">
                        <path class="st0" d="M3.05,37.53c0.07-0.06,0.17-0.1,0.19-0.17c0.84-2.4,2.63-3.44,5.08-3.65c6.42-0.54,12.83-1.13,19.25-1.7  c2.1-0.19,4.19-0.4,6.29-0.56c0.63-0.05,0.94-0.33,1.18-0.9c3.42-8.06,6.86-16.11,10.32-24.15c1.42-3.29,5.37-4.2,7.97-1.87  c0.68,0.61,1.09,1.38,1.45,2.2c3.36,7.88,6.74,15.76,10.08,23.65c0.33,0.77,0.76,1.02,1.54,1.09c6.66,0.58,13.32,1.19,19.97,1.8  c2.01,0.18,4.01,0.36,6.02,0.56c2.17,0.22,3.64,1.41,4.29,3.45c0.66,2.06,0.09,3.88-1.52,5.3c-4.97,4.39-9.96,8.75-14.94,13.12  c-1.58,1.39-3.15,2.8-4.77,4.15c-0.53,0.45-0.68,0.85-0.52,1.55c1.95,8.44,3.85,16.89,5.78,25.33c0.75,3.3-1.07,6.03-4.35,6.43  c-1.24,0.15-2.33-0.27-3.38-0.9c-7.3-4.38-14.62-8.73-21.91-13.14c-0.78-0.47-1.32-0.49-2.11-0.01c-7.31,4.42-14.66,8.79-22,13.17  c-2.68,1.6-5.61,0.98-7.13-1.46c-0.76-1.23-0.91-2.57-0.59-3.97c1.91-8.39,3.8-16.77,5.74-25.15c0.21-0.92,0.04-1.45-0.68-2.08  c-6.32-5.49-12.59-11.03-18.91-16.52c-1.09-0.95-1.9-2.03-2.34-3.39C3.05,38.99,3.05,38.26,3.05,37.53z"/>
                    </svg>
                </div>
                @endif --}}
                    @if(isset($content->user_contents[0]) && $content->user_contents[0]->pivot->is_completed == 1)
                    <span class="svg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="71.3" height="62.387" viewBox="0 0 71.3 62.387">
                            <path id="Icon_open-task" data-name="Icon open-task" d="M0,0V62.387H62.387v-32L53.475,39.3V53.475H8.912V8.912h32L49.821,0ZM62.387,0,35.65,26.737l-8.912-8.912-8.912,8.912L35.65,44.562,71.3,8.912Z" fill="#dcdcdcb3"></path>
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
    <a href="{{route('training.certificates.certificate_dynamic', ['course_registration_id'=> $course_registration_id ] )}}"
        class="" target="_blank">
>>>>>>> 2c04e6c71e1b788113f68e2e7fda78a55c5e94b1
        <div class="text-center course-image certificate certification-card">
            <div class="no-img certificate-img" style="display:flex; align-items: center; justify-content: center;">
                <img src="{{CustomAsset('icons/certificate.svg')}}" height="auto" width="30%">
            </div>
            <div>
                <h2>Congratulations!</h2>
<<<<<<< HEAD
                <span>You have successfully completed the course. </span>
            </div>
        </div>
        @endif
        <div class="card p-30 learning-file activity" style="padding: 0 !important;">
            <h3>Activity</h3>
            <ul style="list-style: none; padding: 0;">
                <?php $lang = app()->getLocale(); ?>
                @foreach($activities as $activity)
                <li>
                @if($activity->type == 'exam')
                <a style="color: #6a6a6a !important;" href="{{ CustomRoute('user.exam',$activity->content_id)}}">
                <img  style="filter: opacity(0.7);" width="28.126" height="28.127" src="{{CustomAsset('icons/activity.svg')}}" alt="activity_icon">
                <span>{{$activity->content_title}} - ({{ json_decode($activity->course_title)->$lang }})</span>
                </a>
                @else
                <a style="color: #6a6a6a !important;" href="{{ CustomRoute('user.course_preview',$activity->content_id)}}">
                <img  style="filter: opacity(0.7);" width="28.126" height="28.127" src="{{CustomAsset('icons/activity.svg')}}" alt="activity_icon">
                <span>{{$activity->content_title}} - ({{ json_decode($activity->course_title)->$lang }})</span>
                </a>
                @endif
                </li>
                @endforeach
            </ul>
=======
                <span style="color: #5D5B5A;">You have successfully completed the course. </span>
            </div>
>>>>>>> 2c04e6c71e1b788113f68e2e7fda78a55c5e94b1
        </div>
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
@endsection
<style>
.svghover {
    fill: #fb4400;
}
</style>
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
