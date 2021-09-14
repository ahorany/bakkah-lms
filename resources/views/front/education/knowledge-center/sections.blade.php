<div id="category-sidebar-hover" class="widget card box-shadow mb-4">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">{{__('education.Knowledge Center Sections')}}</h4>
    </div>
    <div class="card-body py-3 p-0">
        <ul class="list-unstyled">
            @foreach($constants as $constant)
                <li><a href="{{CustomRoute('education.static.knowledge-center', ['post_type'=>$constant->slug])}}">{{$constant->trans_name}}</a></li>
            @endforeach
        </ul>
    </div>
</div>
