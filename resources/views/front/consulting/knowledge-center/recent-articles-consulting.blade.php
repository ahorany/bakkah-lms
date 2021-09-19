<div class="consulting-sidebar-box mb-4 box-shadow">
    <h2>{{__('consulting.Feature Insights')}}</h2>
    <hr>
    <ul class="list-unstyled">
        @foreach($recentArticles as $recentArticle)
            @if(isset($recentArticle->upload->file))

            <li class="media">
                <a href="{{CustomRoute('consulting.static.knowledge-center.single', ['slug'=>$recentArticle->slug])}}">
                    <img src="{{CustomAsset('upload/thumb100/'.$recentArticle->upload->file)}}">
                    <div>
                        <p>{{$recentArticle->title}}</p>
                        <small>{{$recentArticle->published_date}}</small>
                    </div>
                </a>
            </li>
            @endif
        @endforeach
    </ul>
</div> <!-- /.consulting-sidebar-box -->
