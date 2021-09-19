@extends(FRONT.'.consulting.layouts.master')

@section('useHead')
    @include('SEO.head', ['eloquent'=>$post??null])
@endsection

@section('content')

<?php
// use App\Helpers\Recaptcha;
?>
{{-- {!! Recaptcha::script() !!} --}}

    @include(FRONT.'.consulting.Html.page-header', ['title'=>$post->trans_title])

    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="event-details">
                        {{-- <h1 class="mb-4">{{$post->trans_title}}</h1> --}}

                            @php
                                $session_start_time = \Carbon\Carbon::parse($post->session_start_time)->format('Y-m-d H:i:s');
                                $session_start_time_for_counter = \Carbon\Carbon::parse($post->session_start_time)->format('d/m/Y H:i');
                                $session_end_time = \Carbon\Carbon::parse($post->session_end_time)->format('Y-m-d H:i:s');
                                $today_now = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
                                // echo $session_start_time.' - '.$today_now.' - '.$post->video_link;
                                // {{-- 2020-12-14 06:41:20 --}}
                                $session_time_from = \Carbon\Carbon::parse($post->session_start_time)->format('H:iA');
                                $session_time_to = \Carbon\Carbon::parse($post->session_end_time)->format('H:iA');
                            @endphp


                            @if (!is_null($post->video_link) && ($session_start_time < $today_now))

                                <div class="responsive-video">

                                    <?php
                                        $url = $post->video_link;
                                        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);
                                        $youtube_id = 'https://www.youtube.com/embed/'.$match[1];
                                    ?>

                                    {{-- <iframe width="500" height="500" src="{!! $youtube_id !!}">
                                    </iframe> --}}

                                    <div class="youtube-player" data-id="{{$match[1]}}"></div>

                                </div>

                            @else

                                <div class="event-img">

                                    <img class="w-100 h-auto wp-post-image" src="{{CustomAsset('upload/full/'.$post->upload->file)}}" alt="">
                                    <br><br><br><br> <!-- <img src="images/single-event.png" alt=""> -->



                                    {{-- <span class="date">8 <small>Jan</small></span> --}}
                                    <span class="date">{{ \Carbon\Carbon::parse($post->session_start_time)->format('j') }}<small>{{ $post->session_from_month }}</small><small>{{ \Carbon\Carbon::parse($post->session_start_time)->format('Y') }}</small></span>

                                    @if ($session_start_time >= $today_now)
                                        <div class="time-countdown">
                                            <script>

                                                const second = 1000,
                                                minute = second * 60,
                                                hour = minute * 60,
                                                day = hour * 24;

                                                var $session_start_time_for_counter = '<?php echo $post->session_start_time; ?>';

                                                let countDown = new Date($session_start_time_for_counter).getTime(),
                                                xx = setInterval(function() {
                                                    let now = new Date().getTime(),
                                                    distance = countDown - now;
                                                    document.getElementById('days').innerText = Math.floor(distance / (day)),
                                                    document.getElementById('hours').innerText = Math.floor((distance % (day)) / (hour)),
                                                    document.getElementById('minutes').innerText = Math.floor((distance % (hour)) / (minute)),
                                                    document.getElementById('seconds').innerText = Math.floor((distance % (minute)) / second);

                                                }, second)

                                            </script>

                                            <div><span id="days"></span>{{__('consulting.Days')}}</div>
                                            <div><span id="hours"></span>{{__('consulting.Hours')}}</div>
                                            <div><span id="minutes"></span>{{__('consulting.Minutes')}}</div>
                                            <div><span id="seconds"></span>{{__('consulting.Seconds')}}</div>

                                        </div>
                                    @endif
                                </div>
                            @endif

                    </div> <!-- /.event-details -->

                    <div class="event-info border p-3 rounded my-5">
                        <div class="row align-items-center">
                            <div class="col-md-4 mb-4 mb-md-0">
                                <i class="far fa-clock second-color"></i> <strong>{{__('consulting.start_time')}}</strong> <span>{{ $post->session_start }} | {{ $session_time_from }}</span>
                            </div>
                            <div class="col-md-4 mb-4 mb-md-0">
                                <i class="fas fa-flag second-color"></i> <strong>{{__('consulting.end_time')}}</strong> <span>{{ $post->session_end }} | {{ $session_time_to }}</span>
                            </div>

                            <div class="col-md-4">
                                @if ($session_start_time >= $today_now)
                                        <button id="registerBtn" class="btn btn-dark btn-block btn-lg">{{__('consulting.Register')}}</button>
                                @else
                                    <button class="btn btn-primary btn-block btn-lg" disabled>{{__('consulting.registration_over')}}</button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="event-tabs my-5">
                        <div class="nav nav-pills flex-column flex-sm-row" id="pills-tab" role="tablist">
                            <span class="flex-sm-fill nav-link btn btn-light active" data-toggle="pill">{{__('consulting.Webinar Description')}}</span>

                        </div>
                        <div class="tab-content border rounded p-3" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="event-description" role="tabpanel" aria-labelledby="pills-home-tab">
                                <p>{!! $post->trans_details !!}</p>
                                @if (!is_null($post->zoom_link) && ($session_end_time >= $today_now))
                                    <a href="{!! $post->zoom_link !!}" class="btn btn-dark text-white" target="_blank">{{__('consulting.Join us Now!')}}</a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="share-box  border p-3 rounded my-5">
                        @include(FRONT.'.Html.share')
                    </div>

                </div> <!-- /.col-md-8 -->
            </div> <!-- /.row -->
        </div> <!-- /.container -->
    </section>

    <div class="modal fade" id="webinar_registration" tabindex="-1" role="dialog" aria-labelledby="webinar_registrationTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title boldfont" id="webinar_registrationTitle">{{__("consulting.Webinar Registration")}}</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

            <form method="post" id="registration_form" action="{{route('consulting.static.webinarRegistration', ['slug'=>$post->slug])}}" target="hidenFrame">
                <div class="container">
                  <div class="row">
                    @csrf
                    {{-- {!! Recaptcha::execute() !!} --}}
                      <?php
                          $webinar_id = $post->id;
                      ?>
                    <input type="hidden" name="webinar_id" value="<?php echo $webinar_id; ?>">
                    <div class="col-12 col-md-6">

                      <div class="form-group">
                      <label>{{__("consulting.Full Name")}}</label><br>
                        <input type="text" name="en_name" value="" class="form-control">
                      </div>

                      <div class="form-group">
                        <label>{{__("consulting.Email")}}</label><br>
                        <input class="form-control" type="text" name="email" id="email">
                      </div>

                      <div class="form-group">
                        <label>{{__("consulting.Mobile")}}</label><br>
                        <input type="text" name="mobile" value="" class="form-control">
                      </div>

                        <button name="registerBtn" type="submit" class="btn btn-primary">{{__("consulting.Register")}}</button>
                        <div id="msg" class="mt-3 mb-0 alert "></div>
                        {{-- <p class="msg" style="color: green;font-size: 16px;text-align: center;margin-top: 10px;"></p>s --}}

                    </div>
                    <div class="col-12 col-md-6">

                      <div class="event-info border p-3 rounded mb-3">
                        {{-- <span class="date"><span class="day">8</span> <small class="month">1</small></span> --}}
                        <span class="date">{{ \Carbon\Carbon::parse($post->session_start_time)->format('j') }} {{ $post->session_from_month }} {{ \Carbon\Carbon::parse($post->session_start_time)->format('Y') }}</span>

                          <div class="row align-items-center">

                            <div class="col-md-12 mb-4 mb-md-4" name="the_excerpt">
                              <h3>{{$post->trans_title}}</h3>
                              {{$post->trans_excerpt}}
                            </div>

                              <div class="col-md-12 mb-2 mb-md-0">
                                  <i class="far fa-clock second-color"></i> <strong>{{__("consulting.start_time")}}</strong> <span>{{ $session_time_from }}</span>
                              </div>
                              <div class="col-md-12 mb-2 mb-md-0">
                                  <i class="fas fa-flag second-color"></i> <strong>{{__("consulting.end_time")}}</strong> <span>{{ $session_time_to }}</span>
                              </div>
                          </div>
                      </div>

                    </div>

                  </div>
                </div>
            </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__("consulting.Close")}}</button>
              </div>
          </div>
        </div>
      </div>

@endsection
@section('scripts')
<script>
    jQuery(function(){
        jQuery('#registerBtn').click(function(){
            jQuery('#webinar_registration').modal('show');
        });

        jQuery('#registration_form').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                url:$('#registration_form').attr('action'),
                type:'post',
                data: jQuery(this).serialize(),
                beforeSend:function(){
                    jQuery('[name="registerBtn"]').attr('disabled', 'disabled');
                    // jQuery('[name="registerBtn"]').html("Loading...");
                },
                success:function(data){
                    jQuery('[name="registerBtn"]').removeAttr('disabled');
                    // jQuery('[name="registerBtn"]').html("Register");

                    // ===========================
                    var classes = '';
                    var msg_en = '';
                    var msg_ar = '';

                    if(data == 'success'){

                        msg_en = 'Thanks for your registration, we will notify you as soon as the webinar starts.';
                        msg_ar = 'شكرًا لتسجيلك في اللقاء المباشر، سنعلمك بمجرد بدء اللقاء.';
                        classes = 'alert-success';
                        jQuery('[name="en_name"], [name="email"], [name="mobile"]').val('');

                    }else if(data == 'exist'){

                        msg_en = 'You were registerd in this webinar before.';
                        msg_ar = 'لقد سجلت في هذا اللقاء من قبل.';
                        classes = 'alert-info';

                    }else if(data == 'failure'){

                        msg_en = 'Error in registration or invalid email address! send message in contact us page.';
                        msg_ar = 'خطأ في عملية التسجيل أو بريدك الالكتروني خاطئ! أرسل رسالة في صفحة اتصل بنا ';
                        classes = 'alert-danger';

                    }else{  //invalid_data

                        msg_en = 'Error! Enter a vaild data, please.';
                        msg_ar = 'خطأ! أدخل بيانات صحيحة من فضلك. ';
                        classes = 'alert-warning';

                    }

                    var msg_element = document.getElementById('msg');
                    msg_element.classList.remove("alert-success", "alert-info", "alert-danger", "alert-warning");
                    msg_element.classList.add(classes);

                    var msg = msg_en;
                    var l = $("html").attr('lang');
                    if(l=='ar'){
                        msg = msg_ar;
                    }
                    jQuery("#msg").html(msg);
                    // console.log(data);
                    // ===========================

                },
                error:function(e){
                    jQuery('[name="registerBtn"]').removeAttr('disabled');
                    // jQuery('[name="registerBtn"]').html("Register");

                    msg = 'Error! Send message in contact us page with this error please <br>' + e;
                    var l = $("html").attr('lang');
                    if(l=='ar'){
                        msg = 'خطأ! أرسل رسالة في صفحة اتصل بنا بهذا الخطأ من فضلك <br>' + e;
                    }
                    jQuery("#msg").html(msg + '<br>' + e.status + ' : ' + e.statusText,e.responseText);
                    // console.log(e);
                },
            });
            return false;
        });
    });
    </script>

@endsection
