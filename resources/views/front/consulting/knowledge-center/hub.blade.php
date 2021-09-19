@extends(FRONT.'.consulting.layouts.master')

@section('useHead')
    {{-- {{ $constant }}--}}
    @include('SEO.head', ['eloquent'=>\App\Infastructure::find(82)??null])
@endsection

@section('content')

<style>
a:hover {
    color: var(--mainColor);
}
</style>

    <section class="py-3 py-md-5">
        <div class="container">
            <div class="row mb-4 hero">
                <div class="col-md-6 col-lg-8 mb-4">
                  @foreach($posts as $post)
                    <article class="blog-card blog-card--featured bg-light">
                        <figure>
                            @if(isset($post->upload->file))
                                <a href="{{route('education.static.knowledge-center.single', ['slug'=>$post->slug])}}">
                                    <img width="1000" height="300" src="{{CustomAsset('upload/full/'.$post->upload->file)}}" class="w-100 h-auto" alt="">
                                </a>
                            @endif
                        </figure>
                        <div class="blog-card__content p-3 p-lg-5">
                            <h3 class="blog-card__content-title">
                                <a href="{{route('education.static.knowledge-center.single', ['slug'=>$post->slug])}}">
                                    {{$post->title}}
                                </a>
                            </h3>
                            <p>
                                <?php
                                    $content = strip_tags($post->details);
                                    echo substr ($content, 0, 800).'...';
                                ?>
                            </p>
                            <div class="blog-card__meta">
                                {{-- <a href="#" class="blog-card__blog-link">HR</a> --}}
                                <a class="blog-card__blog-link" href="{{route('education.static.knowledge-center', ['post_type'=>$post->postMorph->constant->slug??null])}}">{{$post->postMorph->constant->trans_name??null}}</a>
                                <span class="blog-card__read-time">| {{$post->published_date}}</span>
                                {{-- <a href="#" class="blog-card__read-time">| 18 min read</a> --}}
                            </div>
                        </div>
                    </article>
                        @break
                    @endforeach
                </div>
                <div class="col-md-6 col-lg-4 mb-4">
                    <article class="blog-card">
                        <div class="card box-shadow mb-4 blog-features__list mb-4">
                            <div class="card-header bg-dark text-white">
                                <h4 class="mb-0">{{__('education.Recent Articles')}}</h4>
                            </div>
                            <div class="card-body p-2">
                                @foreach($recentArticles as $post)
                                    <div class="bg-light blog-card__content mb-3 p-3">
                                        <h4 class="blog-card__content-title">
                                            <a href="{{route('education.static.knowledge-center.single', ['slug'=>$post->slug])}}">{{$post->title}}</a>
                                        </h4>
                                        <div class="blog-card__meta">
                                            <a class="blog-card__blog-link" href="{{route('education.static.knowledge-center', ['post_type'=>$post->postMorph->constant->slug??null])}}">{{$post->postMorph->constant->trans_name??null}}</a>
                                            <span class="blog-card__read-time">| {{$post->published_date}}</span>
                                            {{-- <a href="#" class="blog-card__read-time"> | 7 min read</a> --}}
                                        </div>
                                    </div> <!-- /.blog-card__content -->
                                @endforeach
                            </div>
                        </div>
                    </article>
                    <article class="blog-card">
                        <div class="card box-shadow blog-features__list">
                            <div class="card-header bg-dark text-white">
                                <h4 class="mb-0">{{__('education.Most Popular')}}</h4>
                            </div>
                            <div class="card-body p-2">
                                @foreach($most_read as $post)
                                    <div class="bg-light blog-card__content mb-3 p-3">
                                        <h4 class="blog-card__content-title">
                                            <a href="{{route('education.static.knowledge-center.single', ['slug'=>$post->slug])}}">{{$post->title}}</a>
                                        </h4>
                                        <div class="blog-card__meta">
                                            <a class="blog-card__blog-link" href="{{CustomRoute('education.static.knowledge-center', ['post_type'=>$post->postMorph->constant->slug??null])}}">{{$post->postMorph->constant->trans_name??null}}</a>
                                            <span class="blog-card__read-time">| {{$post->published_date}}</span>
                                            {{-- <a href="#" class="blog-card__read-time"> | 7 min read</a> --}}
                                        </div>
                                    </div> <!-- /.blog-card__content -->
                                @endforeach
                            </div>
                        </div>
                    </article>
                </div>
            </div> <!-- /.row -->

            <section class="blog-post-listing">
                <div class="row">
                  @foreach($posts as $key => $post)
                    @if($key > 0)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <article class="blog-card blog-card--one-third bg-light h-100">
                            <figure>
                                {{-- <a href="#">
                                    <img class="w-100" src="https://placehold.it/400x250"
                                        alt="6 Ways to Support Black-Owned Businesses During the Holidays">
                                </a> --}}
                                @if(isset($post->upload->file))
                                    <a href="{{route('education.static.knowledge-center.single', ['slug'=>$post->slug])}}">
                                        <img width="1000" height="300" src="{{CustomAsset('upload/full/'.$post->upload->file)}}" class="w-100 h-auto" alt="">
                                    </a>
                                @endif
                            </figure>
                            <div class="blog-card__content px-3">
                                {{-- <p class="blog-card__content-topic">
                                    <a href="#">
                                        Google Ads
                                    </a>
                                </p> --}}
                                <h3 class="blog-card__content-title">
                                    <a href="{{route('education.static.knowledge-center.single', ['slug'=>$post->slug])}}">
                                        {{$post->title}}
                                    </a>
                                </h3>
                                <div class="blog-card__meta">
                                    <a class="blog-card__blog-link" href="{{route('education.static.knowledge-center', ['post_type'=>$post->postMorph->constant->slug??null])}}">{{$post->postMorph->constant->trans_name??null}}</a>
                                    <span class="blog-card__read-time">| {{$post->published_date}}</span>
                                    {{-- <a href="#" class="blog-card__read-time"> | 5 min read</a> --}}
                                </div>
                            </div>
                        </article> <!-- /.blog-card -->
                    </div>
                    @endif
                  @endforeach

                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="widget card box-shadow text-center submit-paper-box h-100">
                            <div class="card-body">
                                <h2>{{__('consulting.READY TO SUBMIT PAPERS?')}}</h2>
                                <h3>{{__('consulting.Write with us now')}}</h3>
                                <p>{{__('consulting.All full paper submissions')}}</p>
                                <a href="mailto:contactus@bakkah.net.sa?subject=Bakkah Inc. Happy to publish my article&amp;body=Kindly, find the attached article file."
                                    target="_blank" class="btn btn-light boldfont px-4 second-color">{{__('consulting.Submit Now')}}</a>
                            </div>
                        </div>
                    </div>

                    @foreach ($reports as $report)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <aside class="blog-card blog-card--one-third h-100 bg-dark d-flex justify-content-center text-center align-items-center p-4">
                            <div>
                                <h3 style="font-size: 3em;"><a href="{{route('education.static.reports.single', $report->slug)}}" class="text-white">{{$report->trans_title}}</a></h3>
                                <a href="{{route('education.static.reports.single', $report->slug)}}" class=" btn bg-info text-white mt-3 cta--primary">{{__('education.Explore the Report')}}</a>
                            </div>
                        </aside>
                    </div>
                    @break
                    @endforeach

                    @foreach($reports as $key => $report)
                    @if($key > 0)
                    <div class="col-md-6 col-lg-8 mb-4">
                        <article class="blog-card blog-card--two-thirds bg-light h-100">
                            <figure>
                                <?php $img = $report->upload()->where('post_type', 'image')->first(); ?>
                                @if(!is_null($img))
                                    <a href="{{route('education.static.reports.single', ['slug'=>$report->slug])}}">

                                    <img class="w-100" src="{{CustomAsset('upload/full/'.$img->file)}}" alt="{{$report->trans_title}}">

                                    </a>
                                @endif
                            </figure>
                            <div class="blog-card__content px-3">
                                <h3 class="blog-card__content-title">
                                    <a href="{{route('education.static.reports.single', ['slug'=>$report->slug])}}">{{$report->trans_title}}</a>
                                </h3>
                                <div class="blog-card__meta">
                                    <a class="blog-card__blog-link" href="{{route('education.static.knowledge-center', ['post_type'=>$report->postMorph->constant->slug??null])}}">{{$report->postMorph->constant->trans_name??null}}</a>
                                    <span class="blog-card__read-time">| {{$report->published_date}}</span>
                                </div>
                            </div>

                        </article>
                    </div>
                    @endif
                    @endforeach

                    @foreach ($webinars as $webinar)
                    <div class="col-md-6 col-lg-8 mb-4">
                        <article class="blog-card blog-card--two-thirds bg-light h-100">
                            <figure>
                                <a href="{{route('education.static.webinars.single', ['slug'=>$webinar->slug])}}">

                                    <img class="w-100" src="{{CustomAsset('upload/full/'.$webinar->upload->file)}}" alt="{{$webinar->trans_title}}">

                                </a>
                            </figure>
                            <div class="blog-card__content px-3">
                                <h3 class="blog-card__content-title">
                                    <a href="{{route('education.static.webinars.single', ['slug'=>$webinar->slug])}}">{{$webinar->trans_title}}</a>
                                </h3>
                                <div class="blog-card__meta">
                                    <a href="{{route('education.static.webinars.single', ['slug'=>$webinar->slug])}}" class="blog-card__read-time">
                                        {{ $webinar->session_start }} | {{\Carbon\Carbon::parse($webinar->session_start_time)->format('H:iA')}}
                                    </a>
                                </div>
                            </div>
                        </article>
                    </div>
                    @break
                    @endforeach

                    @foreach($webinars as $key => $webinar)
                    @if($key > 0)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <article class="blog-card blog-card--one-third bg-light h-100">
                            <figure>
                                <a href="{{route('education.static.webinars.single', ['slug'=>$webinar->slug])}}">

                                    <img class="w-100" src="{{CustomAsset('upload/full/'.$webinar->upload->file)}}" alt="{{$webinar->trans_title}}">

                                </a>
                            </figure>
                            <div class="blog-card__content px-3">
                                <h3 class="blog-card__content-title">
                                    <a href="{{route('education.static.webinars.single', ['slug'=>$webinar->slug])}}">{{$webinar->trans_title}}</a>
                                </h3>
                                <p class="mt-3 lead">{{$webinar->trans_excerpt}}</p>
                                <div class="blog-card__meta"><a href="" class="blog-card__read-time">
                                    {{ $webinar->session_start }} | {{\Carbon\Carbon::parse($webinar->session_start_time)->format('H:iA')}}
                                </a>
                                </div>
                            </div>
                        </article>
                    </div>
                    @endif
                    @endforeach
                </div>

            </section>
        </div> <!-- /.container -->
    </section>
    <section class="pb-md-5">
        <div class="section-title text-center">
            <h2 class="boldfont">{{__('education.Explore More Topics')}}</h2>
            <p> {{__("education.Ready to brush up on something new? We've got more to read right this way")}} </p>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <a class="second-color" href="{{route('education.static.knowledge-center', ['post_type'=>'knowledge-center'??null])}}">
                        <div class="bg-light explore-box e1 p-5">
                            <h2 class="boldfont text-center m-0">{{__('education.Knowledge Center')}}</h2>
                        </div>
                    </a>
                </div>

                <div class="col-md-4 mb-4">
                    <a class="second-color" href="{{route('education.static.webinars')}}">
                        <div class="bg-light explore-box e2 p-5">
                            <h2 class="boldfont text-center m-0">{{__('education.webinars')}}</h2>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 mb-4">
                    <a class="second-color" href="{{route('education.static.reports')}}">
                        <div class="bg-light explore-box e3 p-5">
                            <h2 class="boldfont text-center m-0">{{__('education.Reports')}}</h2>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    @include(FRONT.'.consulting.knowledge-center.newsletter')
@endsection
