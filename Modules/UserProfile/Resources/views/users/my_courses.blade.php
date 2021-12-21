@extends(FRONT.'.education.layouts.master')

@section('useHead')
    <title>{{__('education.My Courses')}} | {{ __('home.DC_title') }}</title>
@endsection

@section('content')
<style>
.userarea-wrapper{
    background: #fafafa;
}
.courses .line {
    width: 100%;
    background: #fb4400;
    height: 4px;
}
.courses .rating {
    border: none;
    direction: rtl;
  }
  .rating > input { display: none; }

  .courses .rating.star > label {
      width: 20px;
      color: #eaeaea;
  }
  .courses fieldset.rating.star > label:before {
      font-size: 25px;
      content: "\2605";
  }
  .courses .rating.star > label{
    background-color: transparent !important;
  }
  .courses .rating.star:not(:checked) > label:hover,
  .courses .rating.star:not(:checked) > label:hover ~ label {
    color:red!important;
    background-color: transparent !important;
    cursor:pointer;
  }
  .courses .star_rating{
    display:flex;
}
.courses .star_rating>label{
    background: #efefef;
    padding: 10px;
    border-radius: 50%;
    font-weight: 600;
    margin-right: 5px;
  }
  .courses .resume{
    background: #fb4400;
    color: #fff;
    border: none;
    padding: 10px 20px;
    font-size: 17px;
    border-radius: 7px;
    margin-top: 10px;
  }
  .courses .description{
    color: #4c4c4c;
    font-size: 17px;
    padding-left:0 !important;
  }

  .courses .title{
    font-weight: 700;
    margin-bottom: 30px;
}
  .courses a.video-link:hover,
  .courses .my-links a:hover{
      color:#fb4400;
      background:#f9f9f9;
  }
  .courses a.video-link{
    padding: 15px 30px;
    font-size: 17px;
    color: #4f4f4f;
  }

  .courses .my-links a{
    padding: 5px 30px;
    font-size: 17px;
    color: #4f4f4f;
  }
  .courses .activity li{
      margin: 10px 0;
      font-size: 15px;
  }
  .courses .activity .circle {
    width: 10px;
    height: 10px;
    display: inline-block;
    background: gainsboro;
    border-radius: 50%;
}
.card.files {
    box-shadow: 0px 3px 10px 1px #e3e3e3;
}

.students{
    background: #fb4400;
    color: #fff;
    text-align: center;
    padding: 45px 0;
    border-radius: 10px;
    height: 100%;
}
.students label{
    display: block;
    margin:0;
}
.images_group img {
    width: 20%;
    border-radius: 50%;
    margin-left: -8px;
}

    .gold-star{
        color: #f0ad4e;
    }

.gold-star2{
    color: #f00 !important;
}

    .part-star{
        /*background: -webkit-linear-gradient(*/
        /*    180deg , transparent 10% , red 90%);*/
        /*background: linear-gradient(to left, transparent 50%, #e8b30f 50%);*/
        -webkit-background-clip: text !important;
        -webkit-text-fill-color: transparent;
    }
</style>

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


    <div class="userarea-wrapper course">
        <div class="row no-gutters">
            @include('userprofile::users.sidebar')
            <div class="col-md-9 col-lg-10">
                <div class="main-user-content">
                    <div class="p-5 user-info courses">
                            <div class="row">
                                <div class="col-12 col-md-6 col-lg-4 mb-4 px-5 image">
                                    <div class="card p-4" style="width: 100%; border-radius: 10px; border: 1px solid #f2f2f2">
                                        @if($image)
                                          <img class="card-img-top" src="{{CustomAsset('upload/thumb200/'.$image->file)}}">
                                        @endif
                                        <div class="card-body text-center p-0 py-2">
                                            <div class="progress" style="height:5px;">
                                                <div class="progress-bar" role="progressbar" style="background: #fb4400; width: {{$course->users[0]->pivot->progress??0}}%;" aria-valuenow="{{$course->users[0]->pivot->progress??0}}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <small class="num m-0 mt-1" style="color:gray;">{{$course->users[0]->pivot->progress??0}}%</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-lg-8 mb-4 px-5 info">
                                    <div class="px-4 rate_course" style="width: 100%;">
                                        <div class="card-body p-0">
                                            <small>Dashboard / My Course / {{$course->trans_title}}</small>
                                            <h1 style="font-weight: 700;    margin: 5px 0 10px;">{{$course->trans_title}}</h1>
                                            <div class="star_rating">
                                                <label class="total_rate">{{round($total_rate,1)}}</label>
                                                <div class="py-2">
                                                        <span class="star review_star1" data-num="1"><i class="fas fa-star"></i></span>
                                                        <span class="star review_star2" data-num="2"><i class="fas fa-star"></i></span>
                                                        <span class="star review_star3" data-num="3" ><i class="fas fa-star"></i></span>
                                                        <span class="star review_star4" data-num="4"><i class="fas fa-star"></i></span>
                                                        <span class="star review_star5" data-num="5" ><i class="fas fa-star"></i></span>
                                                </div>
                                       </div>
                                            <div class="dropdown">
                                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Reviews
                                                </button>
                                                <div class="dropdown-menu flex" style="width: max-content" aria-labelledby="dropdownMenuButton">
                                                    <div class="p-2">
                                                            <span class="star_review star1" data-num="1"><i class="fas fa-star"></i></span>
                                                            <span class="star_review star2" data-num="2"><i class="fas fa-star"></i></span>
                                                            <span class="star_review star3" data-num="3"><i class="fas fa-star"></i></span>
                                                            <span class="star_review star4" data-num="4"><i class="fas fa-star"></i></span>
                                                            <span class="star_review star5" data-num="5"><i class="fas fa-star"></i></span>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-12 col-lg-8 mb-3 p-5">
                                    <p class="description px-4">{{$course->trans_excerpt}}</p>
                                </div>
                                @if($video)
                                    <div class="col-12 col-md-12 col-lg-4 mb-3 py-3 px-0">
                                        <video style="border: 1px solid gainsboro; border-radius: 15px;" width="100%" height="200px" controls preload="metadata">
                                            <source src="{{CustomAsset('upload/video/'.$video->file)}}#t=0.2">
                                        </video>
                                    </div>
                                @endif
                                @if ($course->contents)
                                <div class="col-12 col-md-12 col-lg-12 m-0 p-0 px-5">
                                    <h2 class="title m-0">Content</h2>
                                </div>

                                <div class="col-12 col-md-12 col-lg-12 mb-3 p-3 col-xl-7 px-5">
                                    <div class="row m-0">
                                        @foreach($course->contents as $key => $section)
                                            <div class="col-12 col-md-12 col-lg-12 mb-3 p-0 col-xl-12">
                                                <div class="card files" style="border: 1.5px solid #e6e6e6; border-radius: 10px; padding: 15px 0; overflow:hidden;">
                                                    <p class="learning_file" style="padding:0 30px;">{{$section->title}}</p>
                                                    <div class="learning_file mb-3" style="padding:0 30px;">
                                                        {!!  $section->details->excerpt??null !!}
                                                    </div>
                                                    @isset($section->contents)
                                                    <div class="my-links">
                                                        @foreach($section->contents as $k => $content)
                                                            <?php
                                                                $class = 'fas fa-file';
                                                                switch($content->post_type){
                                                                case "video" :  $class = 'fas fa-video';    break;
                                                                case "presentation" :  $class = 'fas fa-file-powerpoint';   break;
                                                                case "exam" :  $class = 'fas fa-question-circle';  break;
                                                                }
                                                            ?>
                                                            <a  @if( ( isset($section->contents[($k-1)]->user_contents[0]) || ( isset($course->contents[($key-1)])  && isset($course->contents[($key-1)]->contents[ (count($course->contents[($key-1)]->contents) - 1)]->user_contents[0]) && $k == 0  ) )  || ($key == 0 && $k == 0)  )     href=" @if($content->post_type != 'exam') {{CustomRoute('user.course_preview',$content->id)}} @else {{CustomRoute('user.exam',$content->id)}} @endif" @else style="color: #c1bebe" href="#"  onclick="return false"  @endif    class="d-block">
                                                                <i  class="{{$class}} mr-2"></i>  {{$content->title}}
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                    @endisset

                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                <div class="col-12 col-md-12 col-lg-12 mb-3 p-3 col-xl-5">
                                    <div class="row m-0">
                                        <div class="col-12 col-md-12 col-lg-12">
                                            <div class="card px-5 py-4 pb-5 activity" style="border: 1.5px solid #e6e6e6; border-radius: 10px; padding: 15px 0; overflow:hidden; height:97%;">
                                                <h3 class="title">Activity</h3>
                                                <ul class="p-0">
                                                    <li class="row">
                                                    <div class="col-md-1 col-lg-1 col-1 p-0 dot">
                                                            <div class="circle"></div>
                                                        </div>
                                                        <div class="col-md-11 col-lg-11 col-11 p-0">
                                                            <span>
                                                                Lorem ipsum dolor sit amet consectetur, adipisicing elit. Mollitia similique animi ea. Ut, beatae voluptatem.
                                                            </span>
                                                        </div>
                                                    </li>
                                                    <li class="row">
                                                        <div class="col-md-1 col-lg-1 col-1 p-0 dot">
                                                            <div class="circle"></div>
                                                        </div>
                                                        <div class="col-md-11 col-lg-11 col-11 p-0">
                                                            <span>
                                                                Lorem ipsum dolor sit amet consectetur, adipisicing elit. Mollitia similique animi ea. Ut, beatae voluptatem.
                                                            </span>
                                                        </div>
                                                    </li>
                                                    <li class="row">
                                                    <div class="col-md-1 col-lg-1 col-1 p-0 dot">
                                                            <div class="circle"></div>
                                                        </div>
                                                        <div class="col-md-11 col-lg-11 col-11 p-0">
                                                            <span>
                                                                Lorem ipsum dolor sit amet consectetur, adipisicing elit. Mollitia similique animi ea. Ut, beatae voluptatem.
                                                            </span>
                                                        </div>
                                                    </li>
                                                    <li class="row">
                                                    <div class="col-md-1 col-lg-1 col-1 p-0 dot">
                                                            <div class="circle"></div>
                                                        </div>
                                                        <div class="col-md-11 col-lg-11 col-11 p-0">
                                                            <span>
                                                                Lorem ipsum dolor sit amet consectetur, adipisicing elit. Mollitia similique animi ea. Ut, beatae voluptatem.
                                                            </span>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-7 col-lg-7 pr-0 mb-3">
                                            <div class="card p-4 pr-1" style="height: 100%;">
                                                <h4 style="font-weight:700;">Course Group</h4>
                                                <label style="font-size: 11px; color: gray;">Lean Six Sigma Yellow belt training provides insight to the </label>
                                                <div class="images_group pl-3">

                                                    <img class="img-fluid" src="{{CustomAsset('/images/person1.png')}}" alt="Card image cap">
                                                    <img class="img-fluid" src="{{CustomAsset('/images/person2.png')}}" alt="Card image cap">
                                                    <img class="img-fluid" src="{{CustomAsset('/images/person3.png')}}" alt="Card image cap">
                                                    <img class="img-fluid" src="{{CustomAsset('/images/person4.png')}}" alt="Card image cap">
                                                    <img class="img-fluid" src="{{CustomAsset('/images/person15.png')}}" alt="Card image cap">

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-5 col-lg-5 mb-3">
                                            <div class="students">
                                                <label style="font-size:20px;">New Student</label>
                                                <label style="font-size:25px; font-weight:700;">12</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- </div> -->
                    </div>
                </div>
            </div>
        </div>

    <script>

        function rate() {
           // dropdown stars
            let element =   $('.star'+@json($course->course_rate->rate))
            element.find('i').addClass('gold-star')
            element.prevAll().find('i').addClass('gold-star')
            total_review()
        }

        function total_review(){
            // total starts
            let total =  $('.total_rate').text()
            let total_as_int = parseInt(total)
            let arr =  (total + "").split('.')
            let num_after_point = arr[1]??0;
            let elements_stars = $('.star');
            // remove stars style
            elements_stars.find('i').removeClass('gold-star')
            $('.part-star').removeClass('part-star').css('background', '')
            // add style to total num int
            element =   $('.review_star'+total_as_int)
            element.find('i').addClass('gold-star')
            element.prevAll().find('i').addClass('gold-star')
            if(num_after_point != 0){
                    let num =  (total + "").split('.').pop();
                    num = (parseInt(num) / 10) * 100;
                    element = element.next()
                    element.addClass('part-star');
                    element.css('background',`linear-gradient(to left, #303d47 ${100 - num}%, #e8b30f ${num}%)`);
            }
        }
        $( document ).ready(function() {
            rate();
        });

        $(".star_review").click(function(event){
            let rate =   $(this).data('num')
            $(".star_review i").removeClass('gold-star')
            $(this).find('i').addClass('gold-star')
            $(this).prevAll().find('i').addClass('gold-star')

            // ajax

            $.ajax({
                type: "POST",
                url: @json(CustomRoute('user.rate')),
                data: {
                    'course_id' : {{$course->id}},
                    '_token' : @json(csrf_token()),
                    'rate' : rate
                },
                success: function(response){
                    console.log(response)
                    $('.total_rate').text(parseFloat(response.data).toFixed(1))
                    total_review()
                },
            });
        });


    </script>



@endsection
