<?php
    use Jenssegers\Agent\Agent;
    $agent = new Agent();
?>
<section class="slider">
    <div class="owl-carousel main-slider">
        @foreach($sliders as $slider)
            @if(isset($slider->file))
            <div class="carousel-item">
                <a href="{{$slider->url??'#'}}">
                    @if($agent->isPhone())
                        <img src="{{CustomAsset('upload/thumb450/'.$slider->file)}}" class="d-block w-100" alt="{{$slider->upload->excerpt??''}}" title="{{$slider->upload->title??''}}">
                    @else
                        <img src="{{CustomAsset('upload/full/'.$slider->file)}}" class="d-block w-100" alt="{{$slider->upload->excerpt??''}}" title="{{$slider->upload->title??''}}">
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
</section>
