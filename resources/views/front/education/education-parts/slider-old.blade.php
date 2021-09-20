<?php
    use Jenssegers\Agent\Agent;
    $agent = new Agent();
?>
<section class="slider">
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
                        {{--srcset="{{CustomAsset('upload/thumb450/'.$slider->upload->file)}} 480w,
                        {{CustomAsset('upload/full/'.$slider->upload->file)}} 800w"
                        sizes="(max-width: 600px) 480px, 800px"--}}
                        @if($agent->isPhone())
                            <img src="{{CustomAsset('upload/thumb450/'.$slider->upload->file)}}" class="d-block w-100" alt="{{$slider->upload->title}}">
                        @else
                            <img src="{{CustomAsset('upload/full/'.$slider->upload->file)}}" class="d-block w-100" alt="{{$slider->upload->title}}">
                        @endif
                    </a>
                    <div class="carousel-caption d-none">
                        <h5 class="d-none">{{$slider->title}}</h5>
                        <p class="d-none">{{$slider->excerpt}}</p>
                        <a href="{{$slider->url}}">{{__('education.read_more')}}</a>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    </div>
</section>