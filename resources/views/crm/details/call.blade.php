@if(isset($eloquent->id))
    <div id="course_details">
        <div class="card card-default">
            <div class="card-header">{{$title??__('admin.detail')}}</div>
            <div class="card-body">
                <?php $constants = \App\Constant::where('parent_id', $parent_id??26)->get();
                ?>
                <ul class="list-unstyled">
                    @foreach($constants as $constant)
                        <li class="mb-3">
                            {{$constant->trans_name??null}}
                            @if($eloquent->detail()->where('constant_id', $constant->id)->count()==0)
                                <a target="_blank" href="{{route('admin.details.create', ['master_id'=>$eloquent->id, 'constant_id'=>$constant->id])}}" class="btn btn-outline-primary btn-xs">{{__('admin.add')}}</a>
                            @else
                                <a target="_blank" href="{{route('admin.details.edit', ['detail'=>$eloquent->details()->where('constant_id', $constant->id)->first()->id])}}" class="edit btn-sm btn-outline-primary btn-xs">{{__('admin.edit')}}</a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif
