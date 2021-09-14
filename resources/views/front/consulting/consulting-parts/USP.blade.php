@php
if(str_contains(url()->current(), 'for-corporate')){
    $class = 'mt-5 no-hover';
    $col = 'col-md-6 mb-3';

}else {
    $class = ' bg-light py-5 wow fadeInUp';
    $col = 'col-md-6 col-xl-3 mb-3';
}
@endphp
<section class="business-up {{$class}}">
    <div class="container">
        @if(!str_contains(url()->current(), 'for-corporate'))
        <div class="section-title text-center mb-5">
            <h2>{{__('consulting.Take your Business Up')}}</h2>
            <h3>{!! __('consulting.Only Bakkah has all the critical factor') !!}</h3>
        </div>
        @endif
        <div class="row">
            @foreach($USPs as $USP)
                @if(isset($USP->upload->file))
                    <div class="{{$col ?? 'col-md-3'}} px-2">
                        <div class="fancy-box text-center">
                            <img src="{{CustomAsset('upload/thumb100/'.$USP->upload->file)}}" title="{{$USP->trans_name}}" alt="{{$USP->upload->title}}">
                            <h3>{{$USP->title}}</h3>
                            <p>{{$USP->excerpt}}</p>
                        </div>
                    </div>
                @endif
            @endforeach

        </div>
    </div>
</section>
