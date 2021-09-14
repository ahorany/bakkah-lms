@php
if(str_contains(url()->current(), 'service')){
    $col = 'col-md';
}else {
    $col = 'col-md-2';
}
@endphp
<section class="clients-section py-5 wow fadeInUp">
    <div class="container">
        <div class="section-title text-center">
            <h2>{!! __('consulting.Sample of Our Clients') !!}</h2>
        </div>
        <div class="row justify-content-center">
            @foreach($clients as $client)
                @if(isset($client->file))
                    <div class="col-4 {{$col}}">
                        <img class="w-100 border" src="{{CustomAsset('upload/thumb200/'.$client->file)}}" title="{{$client->trans_name}}" alt="{{$client->upload_title}}">
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</section> <!-- /.clients-section -->
<section>
    <div class="container">
        <hr>
    </div>
</section>
