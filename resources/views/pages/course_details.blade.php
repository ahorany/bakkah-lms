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
    .svghover {
        fill: #fb4400;
    }
    svg {
        cursor: pointer;
        width: 20px;
    }
    .certification-card span {
        color: rgb(106, 106, 106) !important;
        padding: 2px 0;
    }
    .free{
        background: #fb4400;
        color: #fff;
        padding: 2px 10px;
        font-size: 10px;
        border-radius: 20px;
        font-family: 'Lato Bold';
    }
    .text-gift span.text{
        color: rgb(193, 190, 190) !important;
    }
    .gray-icon{
        filter: opacity(0.3) !important;
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

    $src_flag = CustomAsset('icons/flag.svg');
    $src_flag_focus = CustomAsset('icons/flag_focus.svg');

    $course_collect = (collect($course->contents)->groupBy('is_aside'));

    ?>

    <div class="course_details">
        <div class="dash-header course-header d-flex align-items-md-end flex-column flex-md-row px-3">
            <div class="text-center course-image w-30 mb-4 mt-2 mb-md-0">
                <?php
                $url = '';
                if($course->upload != null) {
                    $url = $course->upload->file;
                    $url = CustomAsset('upload/full/'. $url);
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
                @php
                    $type = [
                        '11' => 'self-paced',
                        '13' => 'live-online',
                        '353' => 'exam-simulator',
                        '383' => 'instructor-led',
                    ];
                @endphp
                <span class="badge mx-1 {{ $type[$course->deliveryMethod->id] }}">{{$course->deliveryMethod->trans_name}}</span>
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

                @if(!Gate::allows('preview-gate'))
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

                        @if($course->users[0]->pivot->paid_status != 503)
                            <a href="{!! PAY_COURSE_BAKKAH_URL . $course->ref_id !!}" class="mx-0 px-4 main-color">Pay Now</a>
                        @endif
                    </div>
                @endif
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

        @if (isset($course_collect[0]))
            <div class="row mx-0 mt-3 course-content">
                <div class="col-12 course_info">
                    <h3>{{__('education.Materials')}}</h3>
                </div>
                <div class="col-lg-8 mb-5 mb-lg-0 course_info">
                    @foreach($course_collect[0] as $key => $section)
                        <div class="card learning-file mb-3">
                            <h3>{{$section->title}}</h3>
                            <div style="margin: 0px 30px;">{!! $section->details->excerpt??null !!}</div>
                            @isset($section->contents)
                                <ul>
                                    @foreach($section->contents as $k => $content)
                                        <li>
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
                                                    @else
                                                        <?php
                                                        $preview_url = Gate::allows('preview-gate') ? '?preview=true' : '';
                                                        if($content->post_type != 'exam'){
                                                            $url = CustomRoute('user.course_preview', $content->id).$preview_url;
                                                        }else{
                                                            if(Gate::allows('preview-gate')){
                                                                $url = CustomRoute('training.add_questions', $content->id).$preview_url;
                                                            }
                                                            else{
                                                                $url = CustomRoute('user.exam', $content->id).$preview_url;
                                                            }
                                                        }
                                                        ?>


                                                        <?php
                                                            $content_show = false;
                                                            $popup_pay_status = false;
                                                            if( isset($content->user_contents[0])  ){
                                                                $content_show = true;
                                                            }else if ($section->post_type == "section"){
                                                                 if ($content->paid_status == 504 && $content->status == 1 ){ // if free
                                                                     $content_show = true;
                                                                 }else if( $content->status == 1 || (isset($course->users[0]) && $course->users[0]->pivot->paid_status == 503 && $content->paid_status == 503) ){ // if content paid and user pay course
                                                                     $content_show = true;
                                                                 }else{ // if course paid and user not pay course
                                                                     $popup_pay_status = true; // preview pop up pay now
                                                                 }
                                                           }else if ( $content->status == 1 || (isset($course->users[0]) && $section->post_type == 'gift' && $section->gift->open_after <= $course->users[0]->pivot->progress) ){
                                                                $content_show = true;
                                                            }

                                                         ?>


                                                            <a @if($content_show)
                                                               href="{{$url}}"
                                                               @elseif($popup_pay_status)
                                                                href="#" class="gray-icon" onclick="pupupPay(event,'{!! PAY_COURSE_BAKKAH_URL . $course->ref_id !!}')"
                                                               @else
                                                                href="#" class="gray-icon" onclick="return false"
                                                               @endif
                                                            >

                                                            <img style="filter: opacity(0.7);margin-right: 5px;" width="28.126" height="28.127" src="{{CustomAsset('icons/'.$content->post_type.'.svg')}}" alt="{{$content->title}}">
                                                            @endif
                                                            <span>
                                                                {{$content->title}}
{{--                                                                @if ($content->paid_status == 504)--}}
                                                                @if (isset($course->users[0]) && $course->users[0]->pivot->paid_status == 504 && $content->paid_status == 504)
                                                                    <span>
                                                                        {{-- <span class="mx-1">|</span> --}}
                                                                        <span class="mx-1 free">{{$content->paid_status == 504 ? 'Free' : '' }}</span>
                                                                    </span>
                                                                @endif
                                                            </span>


                                                            <span class="svg">
                                                @if(isset($content->user_contents[0]) && $content->user_contents[0]->pivot->flag == 1)
                                                                    <span class="flag_icon_true">
                                                        @if(file_exists(public_path('icons/file_flag_old.svg')))
                                                                            {!!  file_get_contents(public_path('icons/file_flag_old.svg'))  !!}
                                                                        @endif
                                                    </span>
                                                                @else
                                                                    <span class="flag_icon_false">
                                                        @if(file_exists(public_path('icons/file_flag_old.svg')))
                                                                            {!!  file_get_contents(public_path('icons/file_flag_old.svg'))  !!}
                                                                        @endif
                                                    </span>
                                                                @endif
                                                                @if(isset($content->user_contents[0]) && $content->user_contents[0]->pivot->is_completed == 1)
                                                                    <span>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="52" height="52" viewBox="0 0 52 52">
                                                            <path id="Path" d="M0,24.5A24.5,24.5,0,1,0,24.5,0,24.5,24.5,0,0,0,0,24.5Z" transform="translate(1.5 1.5)" fill="#4cdd42" stroke-width="3" stroke-dasharray="0 0"/>
                                                            <path id="Path-2" data-name="Path" d="M10.516,15.62a2.042,2.042,0,0,1-2.879,0L.491,8.474A2.042,2.042,0,0,1,3.37,5.6l5.707,5.7L19.887.491A2.042,2.042,0,0,1,22.766,3.37h0Z" transform="translate(14.372 17.946)" fill="#fff"/>
                                                        </svg>
                                                    </span>
                                                        @else
                                                            <span>
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="52" height="52" viewBox="0 0 52 52"><path id="Path" d="M0,24.5A24.5,24.5,0,1,0,24.5,0,24.5,24.5,0,0,0,0,24.5Z" transform="translate(1.5 1.5)" fill="#fff" stroke="#ccc" stroke-width="3" stroke-dasharray="0 0"></path> <path id="Path-2" data-name="Path" d="M10.516,15.62a2.042,2.042,0,0,1-2.879,0L.491,8.474A2.042,2.042,0,0,1,3.37,5.6l5.707,5.7L19.887.491A2.042,2.042,0,0,1,22.766,3.37h0Z" transform="translate(14.372 17.946)" fill="#ccc"></path></svg>
                                                            </span>
                                                        @endif
                                                    </span>
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
                    @if(isset($course->users[0]->pivot->progress) && ($course->users[0]->pivot->progress >= $course->complete_progress ))
                        @if(!is_null($course_registration))
                            {{-- class="green mb-1"  --}}
                            <a href="{{route('training.certificates.certificate_dynamic', ['course_registration_id'=> $course_registration->id ] )}}"
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
                    @endif


                        <div class="custom-model-main">
                            <div class="custom-model-inner">
                                <div class="custom-model-wrap">
                                    <div class="close-btn">×</div>
                                    <div class="pop-up-content-wrap">
                                        <div class="congrats">
                                            <div class="text-center">
                                                <div class="no-img certificate-img" style="display:flex; align-items: center; justify-content: center;">
                                                    <img src="{{CustomAsset('icons/open.svg')}}" height="auto" width="30%">
                                                </div>
                                                <div>
                                                    <h1>Unlock Full Course!</h1>
                                                    <p>
                                                        We’re glad you enjoy the course so far. Don’t miss the chance of unlocking the full modules, you’re just one step away!
                                                    </p>
                                                    <a id="pay_btn" target="_blank" href="#" class="main-color px-4 my-4">Pay Now</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-overlay"></div>
                        </div>



                        @if(isset($course_collect[1]))
                            @foreach($course_collect[1] as  $key => $section)
                                <div>
                                    <div class="text-center course-image certificate certification-card exam-simulator">
                                        <div class="no-img certificate-img" style="display:flex; align-items: center; justify-content: center;">
                                            @if(isset($course->users[0]) &&  $section->gift->open_after <= $course->users[0]->pivot->progress)
                                                <img src="{{CustomAsset('icons/lock_open.svg')}}" height="auto" width="15%">
                                            @else
                                                <img src="{{CustomAsset('icons/lock_close.svg')}}" height="auto" width="15%">
                                            @endif
                                        </div>
                                        <div>
                                            <h2 class="mb-4">{{$section->title}}</h2>
                                            <span>
                                                <div style="color: #fb4400 !important;">Complete & Get a Gift </div>
                                                <small style="line-height: revert !important;">Something awesome is waiting for you. But unfortunately, you can’t get your gift till you make a progress in this course.</small>
                                            </span>
                                        </div>
                                        <hr class="my-3">
                                        <div class="learning-file">
                                            @isset($section->contents)
                                                <ul>
                                                    @foreach($section->contents as $k => $content)
                                                        <li>
                                                            @if($content->downloadable==1)
                                                                <a href="{{CustomAsset('upload/files/presentations/'.$content->upload->file)}}" download>
                                                                    <img style="filter: opacity(1);margin-right: 5px;" width="28.126" height="28.127" src="{{CustomAsset('icons/download.svg')}}" alt="{{$content->title}}">
                                                                    @else
                                                                        <?php
                                                                        $preview_url = Gate::allows('preview-gate') ? '?preview=true' : '';
                                                                        if($content->post_type != 'exam'){
                                                                            $url = CustomRoute('user.course_preview', $content->id).$preview_url;
                                                                        }else{
                                                                            if(Gate::allows('preview-gate')){
                                                                                $url = CustomRoute('training.add_questions', $content->id).$preview_url;
                                                                            }
                                                                            else{
                                                                                $url = CustomRoute('user.exam', $content->id).$preview_url;
                                                                            }
                                                                        }
                                                                        ?>

                                                                        <?php
                                                                        $content_show = false;
                                                                        if ($content->status == 1 || (isset($course->users[0]) && $section->post_type == 'gift' && $section->gift->open_after <= $course->users[0]->pivot->progress) ){
                                                                            $content_show = true;
                                                                        }

                                                                        ?>
                                                                        <a @if($content_show)
                                                                           href="{{$url}}"
                                                                           @else
                                                                           class="gray-icon" href="#" onclick="return false"
                                                                            @endif >
                                                                            <img style="margin-right: 5px;" width="28.126" height="28.127" src="{{CustomAsset('icons/'.$content->post_type.'.svg')}}" alt="{{$content->title}}">


                                                                            <span class="text">{{$content->title}}</span>

                                                                             <span class="svg">
                                                                                 {{-- @dd(isset($content->user_contents[0]) && $content->user_contents[0]->pivot->is_completed == 1) --}}
                                                                                @if(isset($content->user_contents[0]) && $content->user_contents[0]->pivot->is_completed == 1)
                                                                                    <span>
                                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="52" height="52" viewBox="0 0 52 52">
                                                                                            <path id="Path" d="M0,24.5A24.5,24.5,0,1,0,24.5,0,24.5,24.5,0,0,0,0,24.5Z" transform="translate(1.5 1.5)" fill="#4cdd42" stroke-width="3" stroke-dasharray="0 0"/>
                                                                                            <path id="Path-2" data-name="Path" d="M10.516,15.62a2.042,2.042,0,0,1-2.879,0L.491,8.474A2.042,2.042,0,0,1,3.37,5.6l5.707,5.7L19.887.491A2.042,2.042,0,0,1,22.766,3.37h0Z" transform="translate(14.372 17.946)" fill="#fff"/>
                                                                                        </svg>
                                                                                    </span>
                                                                                @else
                                                                                    <span>
                                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="52" height="52" viewBox="0 0 52 52"><path id="Path" d="M0,24.5A24.5,24.5,0,1,0,24.5,0,24.5,24.5,0,0,0,0,24.5Z" transform="translate(1.5 1.5)" fill="#fff" stroke="#ccc" stroke-width="3" stroke-dasharray="0 0"></path> <path id="Path-2" data-name="Path" d="M10.516,15.62a2.042,2.042,0,0,1-2.879,0L.491,8.474A2.042,2.042,0,0,1,3.37,5.6l5.707,5.7L19.887.491A2.042,2.042,0,0,1,22.766,3.37h0Z" transform="translate(14.372 17.946)" fill="#ccc"></path></svg>
                                                                                    </span>
                                                                                @endif
                                                                           </span>
                                                                        </a>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>

                                            @endisset
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif



                    @if(count($activities) > 0)
                        @include('Html.activity-card', [
                         'activities'=>$activities,
                         'cls'=>'card p-30 activity',
                     ])
                   @endif

                </div>
            </div>
        @endif
    </div>

@endsection

@section('script')


    <script>
            window.total_rate = {!! json_encode($total_rate) !!}
            window.rate =  @json($course->course_rate->rate??null)

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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

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

        $(".close-btn, .bg-overlay").click(function(){
            $(".custom-model-main").removeClass('model-open');
        });

        function pupupPay(event,url) {
            event.preventDefault()
            $("#pay_btn").attr('href',url);
            $(".custom-model-main").addClass('model-open');
        }
    </script>

@endsection
