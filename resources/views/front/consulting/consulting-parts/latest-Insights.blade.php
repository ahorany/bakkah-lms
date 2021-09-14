@if(!str_contains(url()->current(), 'service'))
    <section>
    <div class="container">
        <hr>
    </div>
</section>
@endif
<section class="latest-insights py-5 wow fadeInUp">
    <div class="container">
        <div class="section-title text-center">
            <h2>{!! __('consulting.Our Latest Insights') !!}</h2>
        </div>

        <div class="row mb-5">
            @foreach($latest_insights as $latest_insight)
                @if(isset($latest_insight->upload->file))
                    <div class="col-md-6">
                        <article class="insight-item">
                            <a href="{{CustomRoute('consulting.static.knowledge-center.single', ['slug'=>$latest_insight->slug])}}">
                                <img src="{{CustomAsset('upload/thumb450/'.$latest_insight->upload->file)}}" class="d-block w-100" alt="{{$latest_insight->upload->title}}">
                            </a>
                            {{-- <div class="categories">
                                <a href="{{CustomRoute('consulting.static.knowledge-center', ['post_type'=>$latest_insight->postMorph->constant->slug])}}">
                                    {{$latest_insight->postMorph->constant->trans_name??null}}
                                </a>
                            </div> --}}
                            <h2>
                                <a href="{{CustomRoute('consulting.static.knowledge-center.single', ['slug'=>$latest_insight->slug])}}">
                                    {{$latest_insight->title}}
                                </a>
                            </h2>
                            <p>{{$latest_insight->excerpt}}</p>
                        </article>
                    </div>
                @endif
            @endforeach
        </div>
        <div class="text-center">
            <a href="{{route('consulting.static.knowledge-center', ['post_type'=>'consulting-insights'])}}" class="btn btn-info">{{__('consulting.See All Insights')}}</a>
        </div>
    </div>

</section> <!-- /.latest-insights -->
