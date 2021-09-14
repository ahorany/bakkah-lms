@extends(FRONT.'.consulting.layouts.master')

@section('useHead')
{{--    {{$constant}}--}}
    @include('SEO.head', ['eloquent'=>\App\Infastructure::find(100)??null])
@endsection

@section('content')

    @include(FRONT.'.consulting.Html.page-header', ['title'=>__('consulting.Reports')])

    <section class="all-alliances py-5">
        <div class="container">
            <div class="row justify-content-center my-5">
                @foreach($posts as $post)
                    <div class="col-md-4">
                        <div class="event-box box-shadow">
                            <?php $img = $post->upload()->where('post_type', 'image')->first(); ?>
                            @if(!is_null($img))
                                <div class="img-wrapper">
                                    <a href="{{route('consulting.static.reports.single', ['slug'=>$post->slug])}}">

                                    <img src="{{CustomAsset('upload/thumb450/'.$img->file)}}" alt="{{$post->upload->excerpt}}" title="{{$post->upload->title}}">

                                    </a>
                                </div>
                            @endif
                            <div class="p-3">
                                <a href="{{route('consulting.static.reports.single', ['slug'=>$post->slug])}}"><h2 class="boldfont">{{$post->trans_title}}</h2></a>
                            <p class="event-description">{{$post->trans_excerpt}}</p>
                                <a href="{{route('consulting.static.reports.single', ['slug'=>$post->slug])}}" class="btn btn-block btn-dark text-white">{{__('consulting.Read More')}}</a>
                            </div>
                        </div>
                    </div>
                @endforeach

                <nav aria-label="Page navigation example">
                    {{ $posts->render() }}
                </nav>
            </div>

        </div>
    </section>

@endsection
