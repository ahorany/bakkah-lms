<?php
use App\Models\Training\CourseRegistration;
use Illuminate\Support\Facades\DB;
?>

@section('useHead')
    <title>{{__('education.Course Tests')}} | {{ __('home.DC_title') }}</title>
@endsection
<a href="{{route('training.coursesReportTest',['id'=>$course_id,'export'=>1])}}" class="export btn-sm">{{__('admin.export')}}</a>
<div class="card-body table-responsive p-0">
    <table class="table table-hover table-condensed text-center">
        <thead>
        <tr>
            <th class="">#</th>
            <th class="text-left">Section</th>
            <th class="text-left">{{__('admin.test')}}</th>
            <th class="">{{__('admin.completedNo')}}</th>
            <th class="">{{__('admin.passedNo')}}</th>
            <th class=""></th>
        </tr>
        </thead>
        <tbody>
            @foreach($tests as $post)
                <tr data-id="{{$post->exam_id}}">
                    <td>
                        <span class="td-title px-1">{{$loop->iteration}}</span>
                    </td>
                    <td class="px-1 text-left">
                        <span style="display: block;">{{ $post->section }} </span>

                    </td>
                    <td class="px-1 text-left">
                        <span style="display: block;">{{ $post->content_title }} </span>

                        {{-- <a v-if="entry.post_type != 'exam'" class="cyan" title="Preview" :href="'{{url('/')}}/{{app()->getLocale()}}/user/preview-content/' + entry.id + '?preview=true'" :target="entry.id">
                            <i class="fa fa-folder-open-o" aria-hidden="true"></i>
                        </a> --}}

                    </td>
                    <td>
                        <span style="display: block;">{{ $post->completed }} </span>
                    </td>
                    <td>
                        <span style="display: block;">{{  $post->passess }} </span>
                    </td>
                    <td>

                        <a class="nav-link cyan" target="_blank" href="{{route('training.exam_preview',['content_id'=>$post->content_id])}} " title="Preview" style=" display: inline-block">
                            @include('training.reports.svg_report.preview')
                        </a>

                        <a class="nav-link cyan" target="_blank" href="{{route('training.testUsers',['exam_id'=>$post->exam_id,'content_id'=>$post->content_id])}} "  title="Users" style=" display: inline-block">
                            @include('training.reports.svg_report.users')
                        </a>
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
</div>
{{$paginator->render()}}
