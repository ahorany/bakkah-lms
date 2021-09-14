@extends(FRONT.'.education.layouts.master')

@section('useHead')
    @include('SEO.head', ['eloquent'=>$course??null])
    @include(FRONT.'.social_scripts.rich-result', ['course'=>$course??null])
@endsection

@section('content')

    @include(FRONT.'.education.products.single.header')
    @include(ADMIN.'.Html.alert')

    <section class="main-content">
        {{-- @if(auth()->check())
        @if(auth()->user()->id==2)
            @dump($option_slug)
        @endif
        @endif --}}
        <div class="course-content-section">
            @if($option_slug == 'auto-date')
                @if (!empty($CardsSingles))
                    @if(isset($CardsSingles[0]->session_id) && !is_null($CardsSingles[0]->session_id))
                        @if($sessions->whereNotNull('session_id')->where('option__post_type', 'online-training')->count() != 0)
                            @include(FRONT.'.education.products.single.sessions', ['sessions'=>$sessions])
                        @else
                            <br>
                        @endif
                    @else
                        <br>
                    @endif
                @endif
            @endif

            @foreach($constants as $constant)
                @foreach($constant->details()->where('detailable_id', $course->id)
                ->where('detailable_type', 'App\Models\Training\Course')->get() as $detail)

                    @include(FRONT.'.education.products.single.components.'.$constant->post_type)

                    @if ($detail->constant_id==28)

                        <div class="container">
                            <div class="row">
                                <div class="col-8">
                                    <div class="row">
                                            @foreach($CardsSingles as $CardsSingle)
                                                <?php
                                                    $id = $CardsSingle->constant_id??0;
                                                    $id_name = App\Helpers\Lang::TransTitle($CardsSingle->constant_name)??$CardsSingle->constant_id;
                                                    // $id_name = json_decode($CardsSingle->constant_name)->en??$CardsSingle->constant_id;
                                                ?>
                                                {{-- @dump($id)
                                                @dump($course->trainingOptions->where('constant_id', $CardsSingle->constant_id)->first()->uploads()->where('post_type', 'teaser_video')->first()->file) --}}

                                                @if(isset($course->trainingOptions->where('constant_id', $CardsSingle->constant_id)->first()->uploads()->where('post_type', 'teaser_video')->first()->file))
                                                    <div class="col">
                                                        <!-- Button trigger modal -->
                                                        <button type="button" class="d-block btn btn-info p-2 mt-2 text-danger" data-toggle="modal" data-target="#teaser_video_{{$id}}">
                                                            {{ __('education.Watch Intro Video') }} - {{$id_name}} <i class="fas fa-video p-1 text-white"></i>
                                                        </button>
                                                        {{-- <button type="button" class="d-block p-0 mt-2 text-danger border-0 bg-transparent" data-toggle="modal" data-target="#teaser_video_{{$id}}">
                                                            {{ __('education.Watch Intro Video') }} - {{$id_name}} <i class="fas fa-video card p-1 shadow main-color"></i>
                                                        </button> --}}

                                                        <div class="modal fade" id="teaser_video_{{$id}}" tabindex="-1" role="dialog" aria-labelledby="teaser_video_{{$id}}Title" aria-hidden="true">
                                                            <div class="modal-dialog modal-lg" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                <h5 class="modal-title boldfont" id="teaser_video_{{$id}}Title">{{$id_name}}</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                                </div>
                                                                <div class="modal-body embed-responsive embed-responsive-16by9">
                                                                    {{-- width="480" height="360"  --}}
                                                                    <video oncontextmenu="return false;" class="embed-responsive-item video_show" controls controlsList="nodownload" src="{{CustomAsset('upload/video/'.$course->trainingOptions->where('constant_id', $CardsSingle->constant_id)->first()->uploads()->where('post_type', 'teaser_video')->first()->file)}}"></video>

                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__("education.Close")}}</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                @endforeach

            @endforeach

            {{-- @if($course->id!=32 && $course->id!=35 && $course->id!=36 && $course->id!=37 && $course->id!=38) --}}
            @if($option_slug == 'auto-date')
                @foreach($constants as $constant)
                    @foreach($constant->postMorph()->where('postable_id', $course->id)
                    ->where('postable_type', 'App\Models\Training\Course')->get() as $postMorph)
                        @include(FRONT.'.education.products.single.components.'.$constant->post_type, ['type'=>'course-outline'])
                    @endforeach
                @endforeach

                @foreach($CardsSingles as $CardsSingle)

                    @foreach($constants->where('id', 23)->first()->postMorph()->where('postable_id', $CardsSingle->training_option_id)
                    ->where('postable_type', 'App\Models\Training\TrainingOption')->get() as $postMorph)
                        @include(FRONT.'.education.products.single.components.faqs', ['type' => $CardsSingle->option__post_type])
                    @endforeach

                @endforeach

            @endif

            <?php $path = FRONT.'.education.education-parts'; ?>
            @include($path.'.USP')
            @include($path.'.clients')

            <div class="container">
                <div class="row">
                    <div class="col-md-8">
                        @include($path.'.related-courses')
                        @include($path.'.related-articles')
                    </div>
                </div>
            </div>

        </div>

    </secrion>
@endsection
