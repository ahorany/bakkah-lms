<section class="upcoming-courses py-5 wow fadeInUp">
    <div class="container">
        <div class="d-flex justify-content-between">
            <h2>{!! __('education.Upcoming Courses')!!}</span></h2>
            <ul class="nav mb-3" id="pills-tab" role="tablist">
                @foreach($course_menus as $course_menu)
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{$loop->index==0?'active':''}}" data-toggle="pill" href="#course-tab-{{$loop->iteration}}">{{$course_menu->trans_name}}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="tab-content mt-3 mb-5" id="pills-tabContent">

        @foreach($course_menus as $course_menu)
            <div class="tab-pane fade show {{$loop->index==0?'active':''}}" id="course-tab-{{$loop->iteration}}">
                <div class="upcoming-course-slider owl-carousel owl-theme">
                    @foreach($course_menu->postMorph as $postMorph)
                        @if(isset($postMorph->postable->upload()->where('post_type', app()->getLocale().'_image')->first()->file))
                            <div class="slide-course-item shadow">
                                <a href="#">
                                    <img src="{{CustomAsset('upload/full/'.$postMorph->postable->upload()->where('post_type', app()->getLocale().'_image')->first()->file)}}" title="{{$postMorph->postable->trans_title}}" alt="{{$postMorph->postable->trans_title}}">
                                </a>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endforeach

    </div> <!-- /.tab-content -->
</section> <!-- /.upcoming-courses -->
