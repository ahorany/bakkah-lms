<ol class="breadcrumb">
    {{-- <li><a href="{{CustomRoute('user.home')}}">Dashboard</a></li> --}}
    {{-- <li><a href="{{CustomRoute('user.home')}}">My Courses</a></li> --}}
    {{-- <li style="text-transform: capitalize;"> {{$title}}</li> --}}
    <li style="text-transform: capitalize;">
        <a href="{{route('user.course_details', ['course_id'=>$course_id])}}">{{ $course_title }}</a>
    </li>
    @if(isset($section_title))
        <li><a href="{{route('user.course_details', ['course_id'=>$course_id])}}">{{$section_title}}</a></li>
    @endif
    {{-- @if(isset($content_title))
    <li>
        <strong style="text-transform: capitalize;">{{$content_title}}</strong>
    </li>
    @endif --}}
</ol>
