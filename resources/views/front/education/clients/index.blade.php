@extends(FRONT.'.education.layouts.master')

@section('useHead')
{{--    {{$constant}}--}}
    @include('SEO.head', ['eloquent'=>\App\Infastructure::find(74)??null])
@endsection

@section('content')

    @include(FRONT.'.education.Html.page-header', ['title'=>__('education.Clients')])

    <section class="all-clients py-5">
        <div class="container">
            <div class="row">
                @foreach($posts as $post)
                <div class="col-6 col-md-3">
                    <img width="235" height="153" class="w-100 wp-post-image" src="{{CustomAsset('upload/thumb300/'.$post->upload->file)}}" title="{{$post->trans_name}}" alt="{{$post->trans_name}}">
                </div>
                @endforeach
            </div> <!-- /.row -->
            <nav aria-label="Page navigation example">
                {{ $posts->render() }}
            </nav>
        </div> <!-- /.container -->
    </section>

@endsection
