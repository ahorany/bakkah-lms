@extends(FRONT.'.consulting.layouts.master')

@section('useHead')
{{--    @include('SEO.head', ['eloquent'=>\App\Models\Admin\Post::find(307)??null])--}}
    @include('SEO.head', ['eloquent'=>\App\Infastructure::find(76)??null])
@endsection

@section('content')

    @include(FRONT.'.consulting.consulting-service.page-header')

    <!-- |||||||||||||||||||||||||| MAIN CONTENT OF PM SERVICES |||||||||||||||||||||||||||||| -->
    <div class="main-content py-5">
        <div class="container container-padding">
            <div class="row">
                @foreach($posts as $service)
                    @if(isset($service->upload->file))
                        <div class="col-sm-4">
                            <div class="slide-service-item shadow">
                                <span class="bg"></span>
                                <a href="{{CustomRoute('consulting.static.consulting-service.single', ['slug'=>$service->slug])}}">
                                    <img src="{{CustomAsset('upload/full/'.$service->upload->file)}}" alt="{{$service->upload->title}}">
                                    <h2>{{$service->title}}</h2>
                                    <p>{{$service->excerpt}}</p>
                                    <i class="fas fa-long-arrow-alt-right"></i>
                                </a>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
        <!-- <div class="extra_space"></div> -->
    </div>
    <!-- ||||| MAIN CONTENT OF PM SERVICES ||||| -->

@endsection
