@extends(FRONT.'.consulting.layouts.master')

@section('useHead')
    @include('SEO.head', ['eloquent'=>\App\Infastructure::find(80)??null])
@endsection

@section('content')

    @include(FRONT.'.consulting.Html.page-header', [
        'css'=>'bg-light',
        'title'=>__('consulting.clients'),
    ])

    <!-- |||||||||||||||||||||||||| MAIN CONTENT OF PM SERVICES |||||||||||||||||||||||||||||| -->
    <div class="all-clients py-5">
        <div class="container">
            <div class="row">
                @foreach($clients as $client)
                    @if(isset($client->upload->file))
                        <div class="col-6 col-md-3">
                            <img width="235" height="153" class="w-100 wp-post-image" src="{{CustomAsset('upload/thumb200/'.$client->upload->file)}}" title="{{$client->trans_name}}" alt="{{$client->upload->title}}">
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
        <!-- <div class="extra_space"></div> -->
    </div>
    <!-- ||||| MAIN CONTENT OF PM SERVICES ||||| -->

@endsection
