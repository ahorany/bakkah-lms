<section class="consulting slider">
    <div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            @foreach($sliders as $slider)
                @if(isset($slider->upload->file))
                    <li data-target="#carouselExampleCaptions" data-slide-to="{{$loop->index}}" class="{{$loop->index==0?'active':''}}"></li>
                @endif
            @endforeach
        </ol>
        <div class="carousel-inner">
            @foreach($sliders as $slider)
                @if(isset($slider->upload->file))
                    <div class="carousel-item {{$loop->index==0?'active':''}}">
                        <a href="{{$slider->url??'#'}}">
                            <img src="{{CustomAsset('upload/full/'.$slider->upload->file)}}" class="d-block w-100" alt="{{$slider->upload->title}}">
                        </a>
                        <div class="carousel-caption d-none d-md-block">
                            <h5 class="d-none">{{$slider->title}}</h5>
                            <p class="d-none">{{$slider->excerpt}}</p>
                            <a href="{{$slider->url}}">{{__('education.read_more')}}</a>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</section> <!-- /.consulting-slider -->
