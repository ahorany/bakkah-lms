<div id="category-sidebar-hover" class="widget card box-shadow mb-4">
    <div class="card-header bg-dark text-white">
        <h4 class="mb-0">{{__('consulting.consulting-service')}}</h4>
    </div>
    <div class="card-body py-3 p-0">
        <ul class="list-unstyled">
            @foreach($services as $post)
                <li>
                    <a href="{{CustomRoute('consulting.static.consulting-service.single', ['slug'=>$post->slug])}}">{{$post->title}}</a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
