@extends(FRONT.'.consulting.layouts.master')

@section('useHead')
    @include('SEO.head', ['eloquent'=>$post??null])
@endsection

@section('content')

    @include(FRONT.'.consulting.Html.page-header', ['title'=>$post->title])

    <div class="main-content py-5">
        <div class="container container-padding">
            <div class="row">
                <div class="col-sm-12">
                    <div class="headline">
                        {!! $post->details !!}
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
