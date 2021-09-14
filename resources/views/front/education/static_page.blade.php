@extends(FRONT.'.education.layouts.master')

@section('useHead')
    @include('SEO.head', ['eloquent'=>$post??null])
@endsection

@section('content')
    @include(FRONT.'.education.Html.page-header', ['title'=>$post->title??null])

    <div class="main-content py-5">
        <div class="container container-padding">
            <div class="row">
                <div class="col-sm-12">
                    <div class="headline">
                        {!! $post->details??null !!}
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
