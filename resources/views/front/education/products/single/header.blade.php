
<section class="course-info">
    <div class="container">
        <div class="row">

            <div class="col-md-6 col-lg-6">
                <div class="course-content-description">
                    <ol class="list-unstyled">
                        <li><a href="{{route('education.index')}}">{{__('education.home')}}</a></li>
                        <li><a href="{{route('education.courses')}}">{{__('education.courses')}}</a></li>
                        <li>{{$course->trans_title}}</li>
                    </ol>
                    <h1 class="boldfont">{{$course->trans_title}}</h1>

                    <p class="mt-3">{{$course->trans_excerpt}}</p>

                    <div class="d-flex align-items-center mt-3 mb-4">
                        <i class="fas fa-globe"></i>
                        @foreach($course->postMorph()->whereIn('constant_id', [36,37])->get() as $lang)
                            <span class="mx-1">{{$lang->constant->trans_name}} {{($loop->index==1)?',':''}}</span>
                        @endforeach
                        {{-- {!! $course->Bage($course) !!} --}}

                        @if($course->reviews>500)
                            <span class="bg-primary px-3 py-1 rounded-pill">{{__('education.Best Selling')}}</span>
                        @endif
                        @if($course->rating > 3)
                            <span class="mx-3">{{$course->rating}} <i class="fas fa-star"></i> <small style="color: #fdc800;" class="mx-1">{{$course->reviews}} {{__('education.reviews')}}</small></span>
                        {{--        <span> <a href="#"><i class="far fa-heart"></i> {{__('education.Add To Wishlist')}}</a></span>--}}
                        @endif
                    </div>

                    @if(isset($course->uploads()->where('post_type', 'pdf')->where('locale', app()->getLocale())->first()->file))
                        <a href="{{CustomAsset('upload/pdf/'.$course->uploads->where('post_type', 'pdf')->where('locale', app()->getLocale())->first()->file)}}" download=""><span>{{__('education.Download Course Brochure')}}</span> <i class="fas fa-download card p-1 shadow main-color"></i></a>
                    @endif
                    <?php
                        $slugs = array('pmp', 'capm', 'pmi-sp', 'pfmp', 'pgmp', 'pmi-acp', 'rmp', 'pmi-pba');
                    ?>
                    @if (in_array($course->slug, $slugs))
                        <div class="authorize">
                            <h5>{{ __('education.Authorized Training Partner') }}</h5>
                            <img src="{{CustomAsset('front-dist/images/pmp.png')}}" alt="{{ __('education.Authorized Training Partner') }}">
                        </div>
                    @endif

                    {{-- ========== Video ========= --}}

                    @if(isset($course->uploads()->where('post_type', 'intro_video')->first()->file))
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary d-block p-2 mt-2" data-toggle="modal" data-target="#intro_video">
                            {{ __('education.Watch Intro Video') }} <i class="fas fa-video p-1 text-white"></i>
                        </button>

                        <div class="modal fade" id="intro_video" tabindex="-1" role="dialog" aria-labelledby="intro_videoTitle" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                {{-- <div class="modal-header">
                                <h5 class="modal-title boldfont" id="intro_videoTitle">{{__("education.Webinar Registration")}}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div> --}}
                                <div class="modal-body embed-responsive embed-responsive-16by9">
                                    {{-- width="480" height="360"  --}}
                                    <video oncontextmenu="return false;" class="embed-responsive-item" controls controlsList="nodownload" src="{{CustomAsset('upload/video/'.$course->uploads->where('post_type', 'intro_video')->first()->file)}}"></video>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__("education.Close")}}</button>
                                </div>
                            </div>
                            </div>
                        </div>
                    @endif

                </div>

            </div>

            <div class="col-md-6 col-lg-4 offset-lg-2">

                @include(FRONT.'.education.products.single.main-card.index')

            </div>
        </div>
    </div>
</section>
