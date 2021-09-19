@extends(FRONT.'.consulting.layouts.master')

@section('useHead')
    @include('SEO.head', ['eloquent'=>$post??null, 'type' => 'post'])
@endsection

@section('content')
<style>
.progress-container {
  width: 100%;
  height: 5px;
  background: #011F44;
  margin-top: -1px;
}

/* The progress bar (scroll indicator) */
.progress-bar {
  height: 5px;
  background: var(--thirdColor);
  width: 0%;
}

.blog-card__read-time {
    text-transform: uppercase;
    font-size: .8rem;
    color: #80909c;
    margin-bottom: 10px;
    display: block;
}
.related-articles .owl-dots .owl-dot.active span,
.related-articles .owl-dots .owl-dot:hover span {
    background: var(--thirdColor);
}
</style>
<script>
    // When the user scrolls the page, execute myFunction
window.onscroll = function() {myFunction()};

function myFunction() {
  var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
  var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
  var scrolled = (winScroll / height) * 100;
  document.getElementById("myBar").style.width = scrolled + "%";
}
</script>
<section class="bg-secondary">
    <div class="container-fluid p-0">
        <div class="row no-gutters">
            <div class="col-lg-7 px-md-5 align-self-center">
                <div class="p-md-5 p-3 text-white single-post-info">
                    <h1>{{$post->title}}</h1>
                    <p class="author mt-1"><small class="fas fa-user"></small> {!! __('education.Posted By Bakkah Inc') !!}</p>
                    <p class="lead">
                        <?php
                            $content = strip_tags($post->details);
                            echo substr ($content, 0, 500).'...';
                        ?>
                    </p>
                    <div class="row align-items-center bg-white px-4 py-3 mx-0 mt-5 rounded pillar-page-header__offers d-none">
                        <div class="col-lg-4 pillar-page-header__offer-image">
                            <img class="w-100" src="https://blog.hubspot.com/hs-fs/hubfs/Untitled%20design%20(17).png?width=1600&amp;name=Untitled%20design%20(17).png" alt="Untitled%20design%20(17)">
                        </div>
                        <div class="col-lg-8 pillar-page-header__offer-content">
                            <h3 class="main-color">6 Free Blog Post Templates</h3>
                            <p class="second-color">Save time creating blog posts with these free templates.</p>
                            <a class="btn btn-primary" href="" target="_blank">Get it Now</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                {{-- <img class="w-100 h-100" src="https://placehold.it/400x400" alt=""> --}}
                @if(isset($post->upload->file))
                        <img width="1000" height="300" src="{{CustomAsset('upload/full/'.$post->upload->file)}}" class="w-100 h-100 single-post-image" alt="">
                @endif
            </div>
        </div>
    </div>
</section>

    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-12 main-articles order-1 order-md-2">
                    <article class="article-post mb-5">
                        <main>
                            <div class="article-text mb-5">
                                {!! $post->details !!}
                            </div>

                            <div class="my-5">
                                <h3 class="boldfont mb-3">{!! __('education.Related Articles') !!}</h3>
                                <div class="related-articles owl-carousel owl-theme owl-loaded owl-drag">
                                    @foreach($relatedArticles as $post)
                                        <div class="related-articel-box box-shadow">
                                            <div class="img-wrapper">
                                                @if(isset($post->upload->file))
                                                    <a href="{{route('consulting.static.knowledge-center.single', ['slug'=>$post->slug])}}">
                                                        <img width="400" height="250" src="{{CustomAsset('upload/full/'.$post->upload->file)}}" alt="">
                                                    </a>
                                                @endif
                                            </div>
                                            <div class="p-3">
                                                <a class="category" href="{{route('consulting.static.knowledge-center', ['post_type'=>$post->postMorph->constant->slug??null])}}">{{$post->postMorph->constant->trans_name??null}} <i
                                                    class="fas fa-chevron-{{ $post->locale=='ar' ? 'left' : 'right' }}"></i></a>
                                                <h3>{{$post->title}}</h3>
                                                <span class="blog-card__read-time">{{$post->published_date}}</span>
                                                <a href="{{route('consulting.static.knowledge-center.single', ['slug'=>$post->slug])}}" class="btn btn-dark text-white btn-block">{!! __('education.Read More') !!}</a>
                                            </div>
                                        </div>
                                    @endforeach

                                </div> <!-- /.related-articles owl-carousel -->
                            </div> <!-- /.related-articel -->


                            <div class="single-post-share">
                                @include(FRONT.'.Html.share-single')
                            </div> <!-- /.single-post-share -->
                        </main>
                    </article> <!-- /.article-post -->

                </div> <!-- /.col-md-8 -->
            </div> <!-- /.row -->
        </div> <!-- /.container -->
    </section> <!-- /.all-consulting -->

    @include(FRONT.'.consulting.knowledge-center.newsletter')

@endsection
