<section class="page-header consulting-page-header @if(isset($post)) py-2 single-service-header @else py-4 @endif">
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                @if(!isset($post))
                <ol class="list-unstyled">
                    <li><a href="{{route('consulting.index')}}">{{__('app.Home')}}</a></li>
                    <li>{{__('consulting.consulting-service')}}</li>
                </ol>
                @endif
                <h1 class="second-color my-4">@if(isset($postForTitle->title)){{$postForTitle->title}} @else {{__('consulting.Our Services')}} @endif</h1>
                {{--                <p>{{__('consulting.consulting-service-excerpt')}}</p>--}}
                {{--<a href="#">Consultation Services Download <i class="fas fa-download"></i></a>--}}
            </div>
            @if(isset($postForTitle))
                <div class="col-md-4 text-center">
                    <a href="{{route('consulting.for-corporate')}}" class="btn btn-lg btn-info px-4">{{__('consulting.Talk to an Expert')}}</a>
                    {{-- <a href="{{route('consulting.static.contactusIndex', ['request_type'=>$postForTitle->basic_id])}}" class="btn btn-lg btn-info px-4">{{__('consulting.Request Proposal')}}</a> --}}
                    {{-- <div class="p-4 box-shadow request_box">
                        <div class="d-flex align-items-start">
                            <img class="mr-3" src="{{CustomAsset('images/consulting_icon.png')}}" alt="">
                            <div>
                                <p>{{__('consulting.If you’re interested')}}</p>
                            </div>
                        </div>
                        <a href="{{CustomRoute('consulting.static.contactusIndex', ['request_type'=>$post->basic_id])}}" class="btn btn-block btn-info mt-3"> <i class="fas fa-arrow-right"></i> {{__('consulting.Request Proposal')}}</a>
                    </div> --}}

                </div>
            @endif
        </div>
    </div>
</section> <!-- /.page-header -->
