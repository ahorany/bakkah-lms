<div class="col-md-10 col-10">
    <a href="{{route('training.contents',['course_id'=>$course_id])}}"  class="@isset($contents) @if($contents == true) active @else '' @endif @endisset group_buttons mb-1 btn-sm mr-1">{{__('admin.contents')}}</a>
    <a href="{{route('training.units',['course_id'=>$course_id])}}" class="@isset($units) @if($units == true) active @else '' @endif @endisset group_buttons mb-1 btn-sm">Units</a>
    <a href="{{route('training.course_users',['course_id'=>$course_id])}}" class="@isset($users) @if($users == true) active @else '' @endif @endisset group_buttons mb-1 btn-sm">Users</a>
    <a href="{{route('training.role_path',['course_id'=>$course_id])}}" class="@isset($role_path) @if($role_path == true) active @else '' @endif @endisset group_buttons mb-1 btn-sm">Rule and Path</a>
</div>
<div class="col-md-2 col-2 text-right">
    <div class="back">
        <a href="{{route('training.contents',['course_id'=>$back_id])}}" class="cyan mb-1"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>
</div>
