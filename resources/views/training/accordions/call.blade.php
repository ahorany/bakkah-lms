@if(isset($eloquent->id))
    <div id="course_details">
        <div class="card card-default">
            <div class="card-header">{{__('admin.accordion')}}</div>
            <div class="card-body">
                <?php $post_morphs = $eloquent->postMorphs()
                    ->where('table_id', 25)
                    ->with('constant')->get();
                ?>
                <ul class="list-unstyled">
                    @foreach($post_morphs as $post_morph)
                        <li>
                            {{$post_morph->constant->trans_name??null}}
                            <a target="_blank" href="{{route('admin.accordions.index')}}?master_id={{$post_morph->id}}" class="{{($post_morph->accordions()->count()!=0) ? 'edit btn-sm': 'add btn-sm' }} btn-outline-primary btn-xs">
                                @if($post_morph->accordions()->count()==0)
                                    {{__('admin.add')}}
                                @else
                                    {{__('admin.edit')}}
                                @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif
