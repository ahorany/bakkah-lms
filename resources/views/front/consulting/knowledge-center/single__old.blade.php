@extends(FRONT.'.consulting.layouts.master')

@section('content')

    @include(FRONT.'.consulting.Html.page-header', ['title'=>$post->title])

    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 sidebar order-2 order-md-1">

                    @include(FRONT.'.consulting.knowledge-center.search')

                    @include(FRONT.'.consulting.knowledge-center.sections')

                    @include(FRONT.'.consulting.knowledge-center.recent-articles')

                </div> <!-- /.col-md-4 sidebar -->
                <div class="col-md-8 main-articles order-1 order-md-2">
                    <article class="article-post mb-5">
                        <header>
                            <div class="row align-items-center">
                                <div class="col-3 col-md-2">
                                    <div class="published">
                                        {{$post->published_date}}
                                    </div>
                                </div>
                                <div class="col-9 col-md-10">
                                    <div class="category">
                                        <a href="{{route('consulting.static.knowledge-center')}}">{{$post->postMorph->constant->trans_name}}</a>
                                    </div>
                                    <h2>{{$post->title}}</h2>
                                    <div class="author">
                                        <i class="fas fa-user"></i> {!! __('consulting.Posted By Bakkah Inc') !!}. </div>
                                </div>
                            </div>
                        </header>
                        <main>
                            <div class="article-img my-4">
                                <div class="post_image aligncenter">
                                    <img width="1000" height="300" src="{{CustomAsset('upload/full/'.$post->upload->file)}}" class="w-100 h-auto" alt="" >
                                </div>
                            </div>
                            <div class="article-text mb-5">
                                {!! $post->details !!}
                            </div> <!-- /.article-text -->
                            <hr>
                        </main>
                    </article> <!-- /.article-post -->

                    <div class="articel-author">
                        <div class="row align-items-center">
                            <div class="col-3 col-md-2">
                                <img class="rounded-circle w-100" src="{{CustomAsset('images/profile.jpg')}}" alt="">
                            </div>
                            <div class="col-9 col-md-10">
                                <div class="d-flex justify-content-between align-items-end">
                                    <div>
                                        <h4>{!! __('consulting.Posted By Bakkah Inc') !!}.</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="comment-area my-5">
                        <div id="comments" class="comments-area">
                            <!-- <p class="no-comments"></p> -->
                        </div>
                    </div> <!-- /.comment-area -->


                </div> <!-- /.col-md-8 -->
            </div> <!-- /.row -->
        </div> <!-- /.container -->
    </section> <!-- /.all-consulting -->

@endsection
