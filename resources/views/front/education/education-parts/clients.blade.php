@php
if(!str_contains(url()->current(), 'sessions')) {
    $class = ' py-5 wow fadeInUp';
}
@endphp
<div class="clients-section {{$class ?? ''}}">
    <div class="container">
        @if(str_contains(url()->current(), 'sessions'))
            <div class="row flex-row">
                <div class="col-lg-8">
        @endif
        <div class="section-title text-center">
            <h2>{!! __('education.sample_our_clients') !!}</h2>
        </div>
        <div class="row justify-content-center">
            @foreach($clients as $client)
                @if(isset($client->file))
                    <div class="col-4 col-md-2">
                        <img class="w-100 border" src="{{CustomAsset('upload/thumb160/'.$client->file)}}" title="{{$client->trans_name}}" alt="{{$client->upload_title}}">
                    </div>
                @endif
            @endforeach
        </div>
        @if(str_contains(url()->current(), 'sessions'))
                </div>
            </div>
        @endif
    </div>
</div> <!-- /.clients-section -->
