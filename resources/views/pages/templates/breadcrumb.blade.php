<ol class="breadcrumb">
    {{-- <li><a href="{{CustomRoute('user.home')}}">Dashboard</a></li> --}}
    {{-- <li><a href="{{CustomRoute('user.home')}}">My Courses</a></li> --}}
    {{-- <li style="text-transform: capitalize;"> {{$title}}</li> --}}
    <?php
    $preview_url = Gate::allows('preview-gate') ? '?preview=true' : '';
    ?>
    <li style="text-transform: capitalize;">
        <a href="{{route('user.course_details', ['course_id'=>$course_id])}}{{$preview_url}}">{{ $course_title }}</a>
    </li>
    @if(isset($section_title))
        <li><a href="{{route('user.course_details', ['course_id'=>$course_id])}}{{$preview_url}}">{{$section_title}}</a></li>
    @endif
    {{-- @dd($content_title) --}}
    {{-- @if(isset($content_title))
        <li>
            <strong style="text-transform: capitalize;">{{$content_title}}</strong>
        </li>
    @endif --}}
</ol>
