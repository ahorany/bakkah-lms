<section class="testimonials-section pt-5 wow fadeInUp">
    <div class="container">
        <div class="row align-items-center">

            <div class="col-md-5">
                @foreach($testimonials as $testimonial)
                    <div class="tetsimonial-box mb-5">
                        {{$testimonial->trans_excerpt}}
                        <div class="person d-flex align-items-center">
                            <div class="d-flex align-items-center">
                                @if(isset($testimonial->userId->upload->file))
                                <img src="{{CustomAsset('upload/thumb100/'.$testimonial->userId->upload->file)}}" class="d-block w-100" alt="{{$testimonial->userId->upload->title}}">
                                @endif
                                <span class="name ml-3">
                                    {{$testimonial->userId->trans_name}}
                                    {{--<br><small class="main-color">{{$testimonial->course->trans_short_title}}</small>--}}
                                </span>
                            </div>
                            {{--<span class="date ml-auto">{{$testimonial->published_date}}</span>--}}
                        </div>
                    </div> <!-- /.tetsimonial-box -->
                @endforeach
            </div>

            <div class="col-md-6 mx-4">

                @include($path.'.testimonials-section-glance')

            </div>

        </div>
        <hr>
    </div>
</section> <!-- /.testimonials-section -->
