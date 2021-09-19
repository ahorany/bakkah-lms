@if(isset($eloquent->id))
    <div id="course_details">
        <div class="card card-default">
            <div class="card-header">{{__('admin.detail')}}</div>
            <div class="card-body">
                <?php $post_morphs = $eloquent->postMorphs()
                    ->where('table_id', 26)
                    ->with('constant')->get();
                ?>
                <ul class="list-unstyled">
                    @foreach($post_morphs as $post_morph)
                        <li>
                            {{$post_morph->constant->trans_name??null}}
                            @if(!isset($post_morph->detail->id))
                                <a target="{{$post_morph->constant->id}}" href="{{route('admin.details.create')}}?master_id={{$post_morph->id}}" class="btn btn-outline-primary btn-xs">{{__('admin.add')}}</a>
                            @else
                                <a target="{{$post_morph->constant->id}}" href="{{route('admin.details.edit', ['detail'=>$post_morph->detail->id])}}" class="btn btn-outline-primary btn-xs">{{__('admin.edit')}}</a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif
