<div class="widget card box-shadow mb-4">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">{{__('education.Recent Articles')}}</h4>
    </div>
    <div class="card-body">
        <ul class="list-unstyled">
            @foreach($recentArticles as $post)

                <li class="media">
                    <a href="{{route('education.static.knowledge-center.single', ['post_id'=>$post->id])}}">
                        @if(isset($post->upload->file))
                            <img src="{{CustomAsset('upload/thumb100/'.$post->upload->file)}}" alt="{{$post->upload->excerpt??''}}" title="{{$post->upload->title??''}}">
                        @endif
                    </a>
                    <div class="media-body">
                        <h5 class="mt-0 mb-1 boldfont">
                            <a href="{{route('education.static.knowledge-center.single', ['slug'=>$post->slug])}}">{{$post->title}}</a>
                        </h5>
                        <small>{{$post->published_date}}</small>
                    </div>
                </li> <!-- /.media -->
            @endforeach
        </ul>
    </div> <!-- /.card-body -->
</div> <!-- /.widget -->
