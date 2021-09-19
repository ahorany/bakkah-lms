@extends(FRONT.'.education.layouts.master')

@section('useHead')
{{--    {{$constant}}--}}
    @include('SEO.head', ['eloquent'=>\App\Infastructure::find(82)??null])
@endsection

@section('content')

    @include(FRONT.'.education.Html.page-header', ['title'=>__('education.Partners')])

    <section class="py-5">
        <div class="container">
            <div class="row">
                    @foreach($posts as $post)
                    <div class="col-md-4">
                        <div class="alliance-box">
                            @if(isset($post->upload->file))
                                <a href="{{CustomRoute('education.static.partners.single', ['slug'=>$post->slug])}}">
                                    <img width="1000" height="300" src="{{CustomAsset('upload/full/'.$post->upload->file)}}" class="w-100" alt="">
                                </a>
                            @endif
                            <h2>{{$post->trans_name}}</h2>
                            <p class="text-justify">
                                <?php
                                $content = strip_tags($post->trans_details);
                                echo substr ($content, 0, 200).'...';
                                ?>
                            </p>
                        <a class="btn btn-primary" href="{{CustomRoute('education.static.partners.single', ['slug'=>$post->slug])}}">{{__('education.Read More')}}</a>
                        </div>
                    </div>
                    @endforeach
            </div> <!-- /.row -->
            <nav aria-label="Page navigation example">
                {{ $posts->render() }}
            </nav>
        </div> <!-- /.container -->
    </section>

@endsection
