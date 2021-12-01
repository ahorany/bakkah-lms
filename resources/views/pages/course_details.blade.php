@extends('layouts.app')

@section('useHead')
    <title>{{__('education.My Courses')}} | {{ __('home.DC_title') }}</title>
@endsection

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

    <div class="dash-header course-header d-flex align-items-md-center flex-column flex-md-row">
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
                    $url = 'https://ui-avatars.com/api/?background=23354b&color=fff&name=' . $course->trans_title;
                }
            ?>
            @if($image != null)
                <img src="{{CustomAsset('upload/thumb200/'.$course->upload->file)}}" height="135px">
            @else
                <img src="{{CustomAsset($url)}}" height="135px">
            @endif

            <div class="progress">
                <div style="width: {{$course->users[0]->pivot->progress??0}}% !important;" class="bar"></div>
            </div>
            <small>{{$course->users[0]->pivot->progress??0}}% Complete</small>
        </div>
        <div class="mx-md-4">
            <ol class="breadcrumb">
                <li><a href="{{CustomRoute('user.home')}}">Dashboard</a></li>
                <li><a href="{{CustomRoute('user.home')}}">My Courses</a></li>
                <li> {{$course->trans_title}}</li>
            </ol>
            <h1>{{$course->trans_title}}</h1>

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

            <li class="has-dropdown user course-details" style="list-style: none;">
                <a onclick="this.nextElementSibling.classList.toggle('d-none'); return false;" class="nav-link main-button btn btn-primary" href="#">
                    {{__('education.Add a Review')}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="10.125" height="6.382" viewBox="0 0 10.125 6.382">
                        <path id="Path_114" data-name="Path 114" d="M6.382,5.063,0,0V10.125Z"
                                transform="translate(10.125) rotate(90)" fill="#fff" />
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
                                        d="M142.346,199.955l-5.375-2.706-5.4,2.66.915-5.948-4.2-4.313,5.938-.966,2.8-5.326,2.753,5.35,5.934,1.018L141.483,194Z"
                                        transform="translate(-128.289 -183.355)" fill="#c6c6c6" />
                                </svg>
                            </span>

                        </template>
                    </div>
                </div>
            </li>


            {{--   <a href="#" class="btn btn-primary px-4">Resume Course</a>--}}
        </div>
    </div>

    @if ($course->trans_excerpt)
        <div class="row my-4">
            <div class="col-lg-8 col-xl-9">
                <p class="lead light">{{$course->trans_excerpt}}</p>
            </div>

            @if($video)
            <div class="col-lg-4 col-xl-3">
                <div class="card h-100 justify-content-center align-items-center p-5 video-btn">
                    <button><svg xmlns="http://www.w3.org/2000/svg" width="26.818" height="30.542"
                            viewBox="0 0 26.818 30.542">
                            <path id="Path_92" data-name="Path 92" d="M1586.871,1164.139V1133.6l26.818,15.165Z"
                                transform="translate(-1586.871 -1133.597)" fill="#fff" />
                        </svg>
                    </button>
                </div>
            </div>
            @endif
        </div>
    @endif
    @if($video)
        <div class="modal">
            <div class="modal-content">
                <div class="modal-close">x</div>
                <video width="100%" oncontextmenu="return false;" controls="controls" controlslist="nodownload" src="{{CustomAsset('upload/video/'.$video->file)}}" class="embed-responsive-item"></video>
            </div>
        </div>
    @endif

    @if (count($course->contents) > 0)
        <div class="row mt-3 course-content">
            <div class="col-12">
                <h3>CONTENT</h3>
            </div>
            <div class="col-lg-8 mb-5 mb-lg-0">
                @foreach($course->contents as $key => $section)
                        <div class="card learning-file mb-3">
                            <h2>{{$section->title}}</h2>
                            <div style="margin: 0px 40px;">{!!  $section->details->excerpt??null !!}</div>
                            @isset($section->contents)
                                <ul>
                                    @foreach($section->contents as $k => $content)
                                        <li>
                                            <a @if( ( isset($section->contents[($k-1)]->user_contents[0]) || ( isset($course->contents[($key-1)])  && isset($course->contents[($key-1)]->contents[ (count($course->contents[($key-1)]->contents) - 1)]->user_contents[0]) && $k == 0  ) )  || ($content->status == 1)  )     href=" @if($content->post_type != 'exam') {{CustomRoute('user.course_preview',$content->id)}} @else {{CustomRoute('user.exam',$content->id)}} @endif" @else style="color: #c1bebe" href="#"  onclick="return false"  @endif >
                                                <img width="28.126" height="28.127" src="{{CustomAsset('icons/'.$content->post_type.'.svg')}}" alt="Kiwi standing on oval">

                                                <span> {{$content->title}}</span> </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endisset
                        </div> <!-- /.learning-file -->
                @endforeach
            </div>

            <div class="col-lg-4">
                <div class="card p-30 activity">
                    <h2>Activity</h2>
                    {{--<ul>
                        <li>Lean Six Sigma Yellow belt training provides insight to the methodology for process
                            improvement, tools and techniques of Six Sigma.</li>
                    </ul>--}}
                </div>

                {{--<div class="d-flex justify-content-between mt-5 course-group">
                    <div class="persons card p-20">
                        <h3 class="mt-0">Course Group</h3>
                        <!-- <small>Lean Six Sigma Yellow belt training provides insight to the </small> -->
                        <ul>
                            <li><img src="http://placehold.it/50x50" alt=""></li>
                            <li><img src="http://placehold.it/50x50" alt=""></li>
                            <li><img src="http://placehold.it/50x50" alt=""></li>
                            <li><img src="http://placehold.it/50x50" alt=""></li>
                            <li><img src="http://placehold.it/50x50" alt=""></li>
                            <li><img src="http://placehold.it/50x50" alt=""></li>
                            <li>+15</li>
                        </ul>
                    </div>
                    <div class="new-students">
                        <span>New Student</span>
                        <b>12</b>
                    </div>
                </div> --}}
            </div>
        </div>
    @endif
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
@endsection
