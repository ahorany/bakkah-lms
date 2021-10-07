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
  }

  .courses .title{
    font-weight: 700;
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
</style>
    <div class="userarea-wrapper">
        <div class="row no-gutters">
            @include('userprofile::users.sidebar')
            <div class="col-md-9 col-lg-10">
                <div class="main-user-content m-4">
                    <div class="p-5 user-info courses">
                            <div class="row">
                                <div class="col-4 col-md-3 col-lg-3 mb-4 px-3 image">
                                    <div class="card p-4" style="width: 100%; border-radius: 10px; border: 1px solid #f2f2f2">
                                        @isset($course->upload->file)
                                          <img class="card-img-top" src="{{CustomAsset('upload/thumb200/'.$course->upload->file)}}">
                                        @endisset
                                        <div class="card-body text-center p-0">
                                            <div class="rate">
                                                <div class="line"></div>
                                                <small class="num m-0 mt-2" style="color:gray;">25% Complete</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-8 col-md-9 col-lg-9 mb-4 px-3 info">
                                    <div class="px-4" style="width: 100%;">
                                        <div class="card-body p-0">
                                            <small>Dashboard / My Course / {{$course->trans_title}}</small>
                                            <h1 style="font-weight: 700;    margin: 5px 0 10px;">{{$course->trans_title}}</h1>
                                            <div class="star_rating">
                                                <label>4.5</label>
                                                <fieldset class="rating star">
                                                    <input type="radio" id="field6_star5" name="rating2" value="5" /><label class = "full" for="field6_star5"></label>
                                                    <input type="radio" id="field6_star4" name="rating2" value="4" /><label class = "full" for="field6_star4"></label>
                                                    <input type="radio" id="field6_star3" name="rating2" value="3" /><label class = "full" for="field6_star3"></label>
                                                    <input type="radio" id="field6_star2" name="rating2" value="2" /><label class = "full" for="field6_star2"></label>
                                                    <input type="radio" id="field6_star1" name="rating2" value="1" /><label class = "full" for="field6_star1"></label>
                                                </fieldset>
                                            </div>
                                            <button class="resume">Resume Course</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-8 col-lg-8 mb-3 p-3">
                                    <p class="description">{{$course->trans_excerpt}}</p>
                                </div>

                                <div class="col-12 col-md-4 col-lg-4 mb-3 py-3 px-0">
                                <video style="border: 1px solid gainsboro; border-radius: 15px;" width="100%" height="200px" controls>
                                    <source src="https://www.youtube.com/watch?v=CH50zuS8DD0">
                                </video>
                                </div>

                                <div class="col-12 col-md-12 col-lg-12 mb-3 p-3">
                                    <h2 class="title">Content</h2>
                                </div>

                                <div class="col-12 col-md-7 col-lg-7 mb-3 p-3">
                                    <div class="row m-0">
                                            @foreach($course->contents as $key => $section)

                                                <div class="col-12 col-md-12 col-lg-12 mb-3 p-0">
                                                    <div class="card files" style="border: 1.5px solid #e6e6e6; border-radius: 10px; padding: 15px 0; overflow:hidden;">
                                                        <p class="learning_file" style="padding-left:30px;">{{$section->title}}</p>
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
                                                                   <a  @if( (isset($content->user_contents[0])) || ($key == 0 && $k == 0) || isset($section->contents[($k-1)]->user_contents[0])) )   target="_blank"  href=" @if($content->post_type != 'exam') {{CustomRoute('user.course_preview',$content->id)}} @else {{CustomRoute('user.exam',$content->id)}} @endif" @else style="color: #c1bebe" href="#"   @endif    class="d-block">
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

                                <div class="col-12 col-md-5 col-lg-5 mb-3 p-3">
                                    <div class="card px-5 py-4 activity" style="border: 1.5px solid #e6e6e6; border-radius: 10px; padding: 15px 0; overflow:hidden; height:97%;">
                                        <h3 class="title">Activity</h3>
                                        <ul class="p-0">
                                            <li class="row">
                                            <div class="col-md-1 col-lg-1 col-1 p-0">
                                                    <div class="circle"></div>
                                                </div>
                                                <div class="col-md-11 col-lg-11 col-11 p-0">
                                                    <span>
                                                        Lorem ipsum dolor sit amet consectetur, adipisicing elit. Mollitia similique animi ea. Ut, beatae voluptatem.
                                                    </span>
                                                </div>
                                            </li>
                                            <li class="row">
                                                <div class="col-md-1 col-lg-1 col-1 p-0">
                                                    <div class="circle"></div>
                                                </div>
                                                <div class="col-md-11 col-lg-11 col-11 p-0">
                                                    <span>
                                                        Lorem ipsum dolor sit amet consectetur, adipisicing elit. Mollitia similique animi ea. Ut, beatae voluptatem.
                                                    </span>
                                                </div>
                                            </li>
                                            <li class="row">
                                            <div class="col-md-1 col-lg-1 col-1 p-0">
                                                    <div class="circle"></div>
                                                </div>
                                                <div class="col-md-11 col-lg-11 col-11 p-0">
                                                    <span>
                                                        Lorem ipsum dolor sit amet consectetur, adipisicing elit. Mollitia similique animi ea. Ut, beatae voluptatem.
                                                    </span>
                                                </div>
                                            </li>
                                            <li class="row">
                                            <div class="col-md-1 col-lg-1 col-1 p-0">
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

                            </div>
                        <!-- </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(".star label").click(function(){
            $(this).parent().find("label").css({"color": "#eaeaea"});
            $(this).css({"color": "#fb4400"});
            $(this).nextAll().css({"color": "#fb4400"});
            $(this).css({"background-color": "transparent"});
            $(this).nextAll().css({"background-color": "transparent"});
        });
    </script>

@endsection
