@if (count($relatedArticles) > 0)
<div class="my-5">
    <h3 class="boldfont mb-3">{!! __('education.Related Articles') !!}</h3>
    <div class="related-articles owl-carousel owl-theme owl-loaded owl-drag">
        @foreach($relatedArticles as $post)
            <div class="related-articel-box box-shadow">
                <div class="img-wrapper">
                    @if(isset($post->upload->file))
                        <a href="{{route('education.static.knowledge-center.single', ['slug'=>$post->slug])}}">
                            <img width="400" height="250" src="{{CustomAsset('upload/full/'.$post->upload->file)}}" alt="">
                        </a>
                    @endif
                </div>
                <div class="p-3">
                    <a class="category" href="{{route('education.static.knowledge-center', ['post_type'=>$post->postMorph->constant->slug??null])}}">{{$post->postMorph->constant->trans_name??null}} <i
                        class="fas fa-chevron-{{ $post->locale=='ar' ? 'left' : 'right' }}"></i></a>
                    <h3>{{$post->title}}</h3>
                    <span class="blog-card__read-time">{{$post->published_date}}</span>
                    <a href="{{route('education.static.knowledge-center.single', ['slug'=>$post->slug])}}" class="btn btn-primary btn-block">{!! __('education.Read More') !!}</a>
                </div>
            </div>
        @endforeach

    </div> <!-- /.related-articles owl-carousel -->
</div> <!-- /.related-articel -->
@endif

