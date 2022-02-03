
@can('course.contents.list')
<a href="{{route('training.contents',['course_id'=>$course_id])}}"  class="@isset($contents) @if($contents == true) active @else '' @endif @endisset @isset($green) green @else group_buttons @endisset mb-1 btn-sm d-inline-block">{{__('admin.contents')}}</a>
@endcan

@can('course.units.list')
<a href="{{route('training.units',['course_id'=>$course_id])}}" class="@isset($units) @if($units == true) active @else '' @endif @endisset @isset($green) green @else group_buttons @endisset mb-1 btn-sm d-inline-block">Units</a>
@endcan

@can('course.users.list')
<a href="{{route('training.course_users',['course_id'=>$course_id])}}" class="@isset($users) @if($users == true) active @else '' @endif @endisset @isset($green) green @else group_buttons @endisset mb-1 btn-sm d-inline-block">Users</a>
@endcan

@can('rolePath.list')
<a href="{{route('training.role_path',['course_id'=>$course_id])}}" class="@isset($role_path) @if($role_path == true) active @else '' @endif @endisset @isset($green) green @else group_buttons @endisset mb-1 btn-sm d-inline-block">Rule and Path</a>
@endcan
