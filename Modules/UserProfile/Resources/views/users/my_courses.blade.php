@extends(FRONT.'.education.layouts.master')

@section('useHead')
    <title>{{__('education.My Courses')}} | {{ __('home.DC_title') }}</title>
@endsection
@section('content')

    <div class="userarea-wrapper">
        <div class="row no-gutters">
            @include('userprofile::users.sidebar')
            <div class="col-md-9 col-lg-10">
                <div class="main-user-content m-4">
                    <div class="card p-5 user-info">
                        <h4 class="mb-4"><i class="fas fa-graduation-cap"></i> {{ __('education.My Courses') }}</h4>

                        <?php
                            $carts = $user->carts->where('payment_status', 68);
                        ?>
                        @if ($carts->count() > 0)
                            {{-- @if ($user->carts->where('payment_status', 68)->count() > 0)
                            <h5 class="mb-3">{{ __('education.Completed') }}</h5> --}}
                            {{-- <div class="row mb-4 d-none">
                                @foreach ($user->carts->where('payment_status', 68) as $cart)
                                    <div class="col-md-4 col-lg-2 mb-4">
                                        <div class="bg-light course-profile p-2 text-center">
                                            <a href="{{route('education.courses.single', $cart->course->slug??null)}}">
                                                <img class="w-100" src="{{CustomAsset('upload/thumb200/'.$cart->course->upload->file)}}" alt="">
                                            </a>
                                            <a href="{{route('education.courses.single', $cart->course->slug??null)}}">
                                                <h6 class="mt-3">{{$cart->course->trans_title}}</h6>
                                            </a>
                                            @if(isset($cart->session->trainingOption->lms_id))
                                                @if($cart->session->trainingOption->lms_id==343)
                                                    <a class="btn btn-secondary rounded-pill btn-sm pt-0 px-3" target="_blank" href="https://lms.bakkah.com"><em><small>Open LMS</small></em></a>
                                                @elseif($cart->session->trainingOption->lms_id==342)
                                                    <a class="btn btn-secondary rounded-pill btn-sm pt-0 px-3" target="_blank" href="https://learn.bakkah.com/login/index.php"><em><small>Open LMS</small></em></a>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div> --}}
                            {{-- @endif --}}
                            {{-- @if ($user->carts->where('payment_status', '!=', 68)->count() > 0) --}}
                                {{-- <h5 class="mb-3">{{ __('education.Online courses') }}</h5> --}}
                                <div class="row my-courses">
                                    {{-- @dd($user->carts) --}}
                                    @foreach ($carts as $courses)
                                    {{-- @dd($courses) --}}
                                    <div class="col-md-12 col-lg-12 mb-4">
                                        <div class="card mb-3" style="box-shadow: none;">
                                            <div class="row no-gutters" style="background: rgb(251 251 251)">
                                              <div class="col-md-3 py-4 px-3 card-course" data-id="SID-{{$courses->session->id}}">
                                                <div class="card p-4 position-relative" style="width:100%; overflow:hidden; border-radius: 10px;">
                                                    <img src="{{CustomAsset('upload/thumb200/'.$courses->course->upload->file)}}" class="card-img-top" alt="{{$courses->course->trans_title}}">
                                                    <div class="card-body p-2">
                                                      <h5 class="card-title text-center m-0 mb-2">{{$courses->course->trans_title}}</h5>
                                                      <div class="progress" style="height: 2px; width: 80%; margin: 0 auto;">
                                                        <div class="progress-bar w-75" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="background:#fb4400;"></div>
                                                      </div>
                                                    </div>
                                                    <div class="course-card position-absolute"></div>
                                                  </div>
                                              </div>
                                              <div class="col-md-9 px-2 py-4">
                                                <div class="card-body py-2">
                                                  {{-- <h5 class="card-title m-0 mb-2" style="color: #fb4400;">Course Details:</h5> --}}
                                                  <p class="card-text m-0">{{$courses->course->trans_excerpt}}</p>
                                                    <div class="card-text stars pt-3 pb-2 d-none">
                                                        <div class="container px-0">
                                                            <div class="star_rating">
                                                                <fieldset class="rating star">
                                                                    <input type="radio" id="field6_star5" name="rating2" value="5" /><label class = "full" for="field6_star5"></label>
                                                                    <input type="radio" id="field6_star4" name="rating2" value="4" /><label class = "full" for="field6_star4"></label>
                                                                    <input type="radio" id="field6_star3" name="rating2" value="3" /><label class = "full" for="field6_star3"></label>
                                                                    <input type="radio" id="field6_star2" name="rating2" value="2" /><label class = "full" for="field6_star2"></label>
                                                                    <input type="radio" id="field6_star1" name="rating2" value="1" /><label class = "full" for="field6_star1"></label>
                                                                </fieldset>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="links p-4">
                                                    <?php
                                                        $lms_link = "#";
                                                        if($courses->session->trainingOption->lms_course_id > 0 && !is_null($courses->session->trainingOption->lms_course_id)){
                                                            if($courses->session->trainingOption->lms_id==343){
                                                                $lms_link = env('TalentLMS_URL');
                                                            }
                                                            else if($courses->session->trainingOption->lms_id==342){
                                                                $lms_link = env('Moodle_URL');
                                                            }
                                                        }

                                                        $DateTimeNow = date('Y-m-d');
                                                        $session_date_to = $courses->session->date_to;
                                                    ?>
                                                    <a href="{{$lms_link}}" target="x">LMS</a>
                                                    @if(!is_null($courses->session->zoom_link) && ($DateTimeNow <= $session_date_to))
                                                        <a href="{!! $courses->session->zoom_link !!}" target="x">Zoom</a>
                                                    @endif
                                                    {{-- <a href="#">Evaluation</a>
                                                    <a href="#">Attendence</a>
                                                    <a href="#">Exporting Reports</a>
                                                    <a href="#">Infographic</a>
                                                    <a href="#">Course Record</a> --}}
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                    </div>
                                    @endforeach
                                </div>
                            {{-- @endif --}}
                        @else
                            <div>
                                <a class="btn btn-primary" href="{{route('education.courses')}}">{{__('education.Explore courses')}}</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
