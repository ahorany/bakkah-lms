<div class="d-flex justify-content-end flex-wrap head_courses">
    @can('course.contents.list')
        <a href="{{route('training.contents',['course_id'=>$course_id])}}"  class="@isset($contents) @if($contents == true) active @else '' @endif @endisset @isset($green) green @else cyan @endisset mb-1 ml-1 btn-sm d-inline-block">{{__('admin.contents')}}</a>
    @endcan

    @can('course.units.list')
        <a href="{{route('training.units',['course_id'=>$course_id])}}" class="@isset($units) @if($units == true) active @else '' @endif @endisset @isset($green) green @else cyan @endisset mb-1 ml-1 btn-sm d-inline-block">Units</a>
    @endcan

    @can('course.users.list')
        <a href="{{route('training.course_users',['course_id'=>$course_id])}}" class="@isset($users) @if($users == true) active @else '' @endif @endisset @isset($green) green @else cyan @endisset mb-1 ml-1 btn-sm d-inline-block">Users</a>
    @endcan

    @can('rolePath.list')
        <a href="{{route('training.role_path',['course_id'=>$course_id])}}" class="@isset($role_path) @if($role_path == true) active @else '' @endif @endisset @isset($green) green @else cyan @endisset mb-1 ml-1 btn-sm d-inline-block">Rule and Path</a>
    @endcan

    <a href="{{CustomRoute('user.course_details', ['course_id'=>$course_id]) }}?preview=true" class="@isset($green) green @else cyan @endisset mb-1 ml-1">
        <span>Preview</span>
    </a>

    @if(!isset($courses_home))
        <a href="{{route('training.courses.index')}}" class="@isset($green) green @else cyan @endisset mb-1 ml-1">
            <span>Course List</span>
        </a>
        <a href="{{route('training.courses.edit',[$course_id])}}" class="@isset($green) green @else cyan @endisset mb-1 ml-1">
            <i class="fa fa-arrow-left" aria-hidden="true"></i>
            <span>Back</span>
        </a>
    @endisset

</div>
