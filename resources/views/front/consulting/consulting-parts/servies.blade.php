<section class="our-servises py-5 wow fadeInUp">
    <div class="bg-line"></div>
    <div class="container">
        <div class="section-title text-center">
            <h2>{!! __('consulting.our Servies') !!}</h2>
        </div>

        <div class="our-services-slider owl-carousel owl-theme">
            @foreach($services as $service)
                @if(isset($service->upload->file))
                <div class="slide-service-item shadow">
                    <span class="bg"></span>
                    <a href="{{CustomRoute('consulting.static.consulting-service.single', ['slug'=>$service->slug])}}">
                        <img src="{{CustomAsset('upload/full/'.$service->upload->file)}}" alt="{{$service->upload->title}}">
                        <h2>{{$service->title}}</h2>
                        <p>{{$service->excerpt}}</p>
                        <i class="fas fa-long-arrow-alt-right"></i>
                    </a>
                </div>
                @endif
            @endforeach

        </div>

    </div>

</section> <!-- /.our-servises -->
